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
// public function getAll(): array
// {
//     $sql = "SELECT * FROM invoices ORDER BY created_at DESC";
//     $result = $this->db->query($sql);

//     $invoices = [];
//     while ($row = $result->fetch_assoc()) {
//         $invoices[] = $row;
//     }

//     return $invoices;
// }
public function countAll(): int
{
    $result = $this->db->query("SELECT COUNT(*) AS total FROM invoices");
    return (int)$result->fetch_assoc()['total'];
}

// public function getPaginated(int $limit, int $offset): array
// {
//     $stmt = $this->db->prepare(
//         "SELECT * FROM invoices ORDER BY created_at DESC LIMIT ? OFFSET ?"
//     );
//     $stmt->bind_param("ii", $limit, $offset);
//     $stmt->execute();

//     $result = $stmt->get_result();
//     return $result->fetch_all(MYSQLI_ASSOC);
// }
public function getAllWithUsers(): array
{
    $sql = "
        SELECT invoices.*, users.name AS user_name
        FROM invoices
        LEFT JOIN users ON invoices.created_by = users.id
        ORDER BY invoices.created_at DESC
    ";
    $result = $this->db->query($sql);

    $invoices = [];
    while ($row = $result->fetch_assoc()) {
        $invoices[] = $row;
    }

    return $invoices;
}

public function getPaginatedWithUsers(int $limit, int $offset): array
{
    $stmt = $this->db->prepare("
        SELECT invoices.*, users.name AS user_name
        FROM invoices
        LEFT JOIN users ON invoices.created_by = users.id
        ORDER BY invoices.created_at DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
public function myInvoices() {
    $stmt =$this->db->prepare("

    ");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();

}
// Get all invoices of a user
public function getByUser(int $userId): array
{
    $stmt = $this->db->prepare("
        SELECT *
        FROM invoices
        WHERE created_by = ?
        ORDER BY created_at DESC
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
public function getAllInvoicesWithDetails(): array
{
    $stmt = $this->db->prepare("
        SELECT i.*, 
               u.name AS user_name,
               u.email AS user_email,
               u.phone_no AS user_phone,
               u.address AS user_address,
               it.id AS item_id,
               it.item_name AS item_name,
               it.price,
               it.quantity
        FROM invoices i
        LEFT JOIN users u ON i.created_by = u.id
        LEFT JOIN invoice_items it ON i.id = it.invoice_id
        ORDER BY i.invoice_date ASC
    ");

    $stmt->execute();
    $result = $stmt->get_result();

    $invoices = [];
    while ($row = $result->fetch_assoc()) {
        $invoiceId = $row['id'];

        if (!isset($invoices[$invoiceId])) {
            $invoices[$invoiceId] = [
                'id'             => $row['id'],
                'invoice_number' => $row['invoice_number'],
                'invoice_date'   => $row['invoice_date'],
                'due_date'       => $row['due_date'],
                'subtotal'       => $row['subtotal'],
                'tax_rate'       => $row['tax_rate'],
                'tax_type'       => $row['tax_type'],
                'total_amount'   => $row['total_amount'],
                'status'         => $row['status'],
                'user_name'      => $row['user_name'],
                'user_email'     => $row['user_email'],
                'user_phone'     => $row['user_phone'],
                'user_address'   => $row['user_address'],
                'items'          => []
            ];
        }

        if ($row['item_id']) {
            $invoices[$invoiceId]['items'][] = [
                'id'       => $row['item_id'],
                'name'     => $row['item_name'],
                'price'    => $row['price'],
                'quantity' => $row['quantity']
            ];
        }
    }

    return array_values($invoices);
}

}
