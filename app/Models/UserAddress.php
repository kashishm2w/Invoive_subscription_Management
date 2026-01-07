<?php

namespace App\Models;

use App\Core\Model;

class UserAddress extends Model
{
    /**
     * Get all addresses for a user
     */
    public function getByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM user_addresses 
            WHERE user_id = ? 
            ORDER BY is_default DESC, created_at DESC
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $addresses = [];
        while ($row = $result->fetch_assoc()) {
            $addresses[] = $row;
        }
        return $addresses;
    }

    /**
     * Get address by ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM user_addresses WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows ? $result->fetch_assoc() : null;
    }

    /**
     * Get default address for a user
     */
    public function getDefaultByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM user_addresses 
            WHERE user_id = ? AND is_default = 1 
            LIMIT 1
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows ? $result->fetch_assoc() : null;
    }

    /**
     * Create a new address
     */
    public function create(array $data): int
    {
        // If this is the first address or marked as default, set it as default
        $existingAddresses = $this->getByUserId($data['user_id']);
        $isDefault = empty($existingAddresses) || !empty($data['is_default']) ? 1 : 0;

        // If setting as default, unset other defaults first
        if ($isDefault) {
            $this->unsetAllDefaults($data['user_id']);
        }

        $stmt = $this->db->prepare("
            INSERT INTO user_addresses 
            (user_id, full_name, phone, address, city, state, pincode, is_default) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "issssssi",
            $data['user_id'],
            $data['full_name'],
            $data['phone'],
            $data['address'],
            $data['city'],
            $data['state'],
            $data['pincode'],
            $isDefault
        );
        $stmt->execute();
        
        return $this->db->insert_id;
    }

    /**
     * Set an address as default
     */
    public function setDefault(int $id, int $userId): bool
    {
        // First, unset all defaults for this user
        $this->unsetAllDefaults($userId);

        // Then set the new default
        $stmt = $this->db->prepare("
            UPDATE user_addresses 
            SET is_default = 1 
            WHERE id = ? AND user_id = ?
        ");
        $stmt->bind_param("ii", $id, $userId);
        return $stmt->execute();
    }

    /**
     * Unset all default addresses for a user
     */
    private function unsetAllDefaults(int $userId): void
    {
        $stmt = $this->db->prepare("
            UPDATE user_addresses 
            SET is_default = 0 
            WHERE user_id = ?
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    }

    /**
     * Delete an address
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM user_addresses WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
