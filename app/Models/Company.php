<?php
namespace App\Models;
use App\Core\Model;

class Company extends Model
{
    protected string $table = 'company';

    public function getByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM company WHERE user_id = ? LIMIT 1"
        );
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $res = $stmt->get_result();
        return $res->num_rows ? $res->fetch_assoc() : null;
    }

 
    public function getFirst(): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM company ORDER BY id ASC LIMIT 1"
        );
        $stmt->execute();

        $res = $stmt->get_result();
        return $res->num_rows ? $res->fetch_assoc() : null;
    }

public function save(array $data): bool
{
    $existing = $this->getByUserId($data['user_id']);

    if ($existing) {
        $stmt = $this->db->prepare(
            "UPDATE company SET
                company_name = ?, email = ?, phone = ?, address = ?, tax_number = ?
             WHERE user_id = ?"
        );

        $stmt->bind_param(
            "sssssi",
            $data['company_name'],
            $data['email'],
            $data['phone'],
            $data['address'],
            $data['tax_number'],
            $data['user_id']
        );
    } else {
        $stmt = $this->db->prepare(
            "INSERT INTO company
            (user_id, company_name, email, phone, address, tax_number)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "isssss",
            $data['user_id'],
            $data['company_name'],
            $data['email'],
            $data['phone'],
            $data['address'],
            $data['tax_number']
        );
    }

   $companySaved = $stmt->execute();

    return $companySaved;
}

}
