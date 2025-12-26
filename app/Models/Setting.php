<?php

namespace App\Models;

use App\Core\Model;

class Setting extends Model
{
    public function getUserById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT id, name, email, phone_no, address, role FROM users where id=? ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows ? $result->fetch_assoc() : null;
    }

   public function updateUser(
    int $id,
    string $name,
    string $email,
    ?string $phoneNo = null,
    ?string $address = null,
    ?string $password = null
): bool
{
    if ($password) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("
            UPDATE users 
            SET name = ?, email = ?, phone_no = ?, address = ?, password = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "sssssi",
            $name,
            $email,
            $phoneNo,
            $address,
            $passwordHash,
            $id
        );
    } else {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET name = ?, email = ?, phone_no = ?, address = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "ssssi",
            $name,
            $email,
            $phoneNo,
            $address,
            $id
        );
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
