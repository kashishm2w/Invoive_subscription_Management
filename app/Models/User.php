<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM users WHERE email = ?"
        );
        $stmt->bind_param("s", $email);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }
    public function register(string $name, string $email, string $hashedPassword): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')"
        );
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        return $stmt->execute();
    }
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE email = ? LIMIT 1"

        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->num_rows ? $result->fetch_assoc() : null;
    }
}
