<?php

namespace App\Models;

use App\Core\Model;

class Invoice extends Model
{
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO invoices 
            (created_by, client_id, invoice_number, invoice_date, due_date,
             subtotal, tax_type, tax_rate, tax_amount, discount, total_amount, status, notes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iisssdsddddss",
            $data['created_by'],
            $data['client_id'],
            $data['invoice_number'],
            $data['invoice_date'],
            $data['due_date'],
            $data['subtotal'],
            $data['tax_type'],
            $data['tax_rate'],
            $data['tax_amount'],
            $data['discount'],
            $data['total_amount'],
            $data['status'],
            $data['notes']
        );

        $stmt->execute();
        return $this->db->insert_id;
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM invoices WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->num_rows ? $res->fetch_assoc() : null;
    }
    public function getMonthlyTotals(): array
{
    $sql = "
        SELECT MONTH(invoice_date) AS month, SUM(total_amount) AS total
        FROM invoices
        GROUP BY MONTH(invoice_date)
        ORDER BY MONTH(invoice_date)
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
public function getDailyTotals(string $startDate, string $endDate): array
{
    $stmt = $this->db->prepare("
        SELECT DATE(invoice_date) AS date, SUM(total_amount) AS total
        FROM invoices
        WHERE invoice_date BETWEEN ? AND ?
        GROUP BY DATE(invoice_date)
        ORDER BY DATE(invoice_date)
    ");

    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[$row['date']] = (float)$row['total'];
    }

    return $data;
}

}
