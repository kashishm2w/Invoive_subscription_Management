<?php

namespace App\Models;

use App\Core\Model;

class Payment extends Model
{
    /**
     * Create a new payment record
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO payments 
            (invoice_id, user_id, amount, payment_method, transaction_id, status, notes)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iidssss",
            $data['invoice_id'],
            $data['user_id'],
            $data['amount'],
            $data['payment_method'],
            $data['transaction_id'],
            $data['status'],
            $data['notes']
        );

        $stmt->execute();
        return $this->db->insert_id;
    }

    /**
     * Get payment by ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM payments WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows ? $result->fetch_assoc() : null;
    }

    /**
     * Get all payments for an invoice
     */
    public function getByInvoice(int $invoiceId): array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, u.name AS user_name, u.email AS user_email
            FROM payments p
            LEFT JOIN users u ON p.user_id = u.id
            WHERE p.invoice_id = ?
            ORDER BY p.created_at DESC
        ");
        $stmt->bind_param("i", $invoiceId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get all payments for a user
     */
    public function getByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, i.invoice_number, i.total_amount AS invoice_total
            FROM payments p
            LEFT JOIN invoices i ON p.invoice_id = i.id
            WHERE p.user_id = ?
            ORDER BY p.created_at DESC
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get paginated payments for a user
     */
    public function getPaginatedByUser(int $userId, int $limit, int $offset): array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, i.invoice_number, i.total_amount AS invoice_total
            FROM payments p
            LEFT JOIN invoices i ON p.invoice_id = i.id
            WHERE p.user_id = ?
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("iii", $userId, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Count payments for a user
     */
    public function countByUser(int $userId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM payments WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return (int)$stmt->get_result()->fetch_assoc()['total'];
    }

    /**
     * Get total amount paid for an invoice
     */
    public function getTotalPaidForInvoice(int $invoiceId): float
    {
        $stmt = $this->db->prepare("
            SELECT COALESCE(SUM(amount), 0) AS total_paid 
            FROM payments 
            WHERE invoice_id = ? AND status = 'completed'
        ");
        $stmt->bind_param("i", $invoiceId);
        $stmt->execute();
        return (float)$stmt->get_result()->fetch_assoc()['total_paid'];
    }

    /**
     * Get all payments (admin)
     */
    public function getAll(): array
    {
        $result = $this->db->query("
            SELECT p.*, 
                   u.name AS user_name, 
                   u.email AS user_email,
                   i.invoice_number,
                   i.total_amount AS invoice_total
            FROM payments p
            LEFT JOIN users u ON p.user_id = u.id
            LEFT JOIN invoices i ON p.invoice_id = i.id
            ORDER BY p.created_at DESC
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get paginated payments (admin)
     */
    public function getPaginated(int $limit, int $offset): array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   u.name AS user_name, 
                   u.email AS user_email,
                   i.invoice_number,
                   i.total_amount AS invoice_total
            FROM payments p
            LEFT JOIN users u ON p.user_id = u.id
            LEFT JOIN invoices i ON p.invoice_id = i.id
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Count all payments
     */
    public function countAll(): int
    {
        $result = $this->db->query("SELECT COUNT(*) AS total FROM payments");
        return (int)$result->fetch_assoc()['total'];
    }

    /**
     * Update payment status
     */
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE payments SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }
}
