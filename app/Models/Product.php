<?php

namespace App\Models;

use App\Core\Model;

class Product extends Model
{
    public function getAll(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM products ORDER BY id DESC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->num_rows ? $result->fetch_assoc() : null;
    }

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

    public function add(array $data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO products 
            (name, description, price, is_tax_free, quantity, poster) 
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $price       = (float)$data['price'];
        $is_tax_free = (int)($data['is_tax_free'] ?? 0);
        $quantity    = (int)$data['quantity'];

        $stmt->bind_param(
            "ssdiis",
            $data['name'],
            $data['description'],
            $price,
            $is_tax_free,
            $quantity,
            $data['poster']
        );

    return $stmt->execute();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE products 
             SET name = ?, description = ?, price = ?, is_tax_free = ?, quantity = ?, poster = ?, updated_at = NOW() 
             WHERE id = ?"
        );

        $is_tax_free = (int)($data['is_tax_free'] ?? 0);

        $stmt->bind_param(
            "ssdiisi",
            $data['name'],
            $data['description'],
            $data['price'],
            $is_tax_free,
            $data['quantity'],
            $data['poster'],
            $id
        );

        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Search products by name
     */
    public function searchByName(string $search = ''): array
    {
        if (empty($search)) {
            return $this->getAll();
        }

        $stmt = $this->db->prepare(
            "SELECT * FROM products WHERE name LIKE ? ORDER BY id DESC"
        );
        $searchTerm = "%" . $search . "%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}