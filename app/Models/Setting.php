<?php

namespace App\Models;

use App\Core\Model;

class Setting extends Model
{
    public function getUserById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows ? $result->fetch_assoc() : null;
    }

    public function updateUser(int $id, string $name, string $email, ?string $password = null): bool
    {
        if ($password) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $email, $passwordHash, $id);
        } else {
            $stmt = $this->db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $email, $id);
        }

        return $stmt->execute();
    }

    public function getUsers(): array
    {
        $result = $this->db->query("SELECT id, name, email FROM users WHERE role != 'admin' ORDER BY name ASC");
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }
}
