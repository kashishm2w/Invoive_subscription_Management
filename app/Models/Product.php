<?php

namespace App\Models;

use App\Core\Model;

class Product extends Model
{
    // Get all products
    public function getAll(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM products ORDER BY id DESC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Get a product by ID
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->num_rows ? $result->fetch_assoc() : null;
    }

    // ⚠️ LOGIC WARNING: This is NOT real sales data
    public function getMonthlySales(): array
    {
        $sql = "
            SELECT MONTH(created_at) AS month, 
                   SUM(price * quantity) AS total
            FROM products
            GROUP BY MONTH(created_at)
        ";

        $result = $this->db->query($sql);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'month' => (int)$row['month'],
                'total' => (float)$row['total']
            ];
        }

        return $data;
    }

    // Add product ✅ FIXED
    public function add(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO products 
            (name, description, price, tax_percent, quantity, poster) 
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $price       = (float)$data['price'];
        $tax_percent = (float)$data['tax_percent'];
        $quantity    = (int)$data['quantity'];

        $stmt->bind_param(
            "ssddis",
            $data['name'],
            $data['description'],
            $price,
            $tax_percent,
            $quantity,
            $data['poster']
        );

    return $stmt->execute();
    }

    // Update product
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE products 
             SET name = ?, description = ?, price = ?, tax_percent = ?, quantity = ?, poster = ?, updated_at = NOW() 
             WHERE id = ?"
        );

        $stmt->bind_param(
            "ssddisi",
            $data['name'],
            $data['description'],
            $data['price'],
            $data['tax_percent'],
            $data['quantity'],
            $data['poster'],
            $id
        );

        return $stmt->execute();
    }

    // Delete product
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
