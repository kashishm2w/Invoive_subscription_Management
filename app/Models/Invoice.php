<?php

namespace App\Models;

use App\Core\Model;

class Invoice extends Model
{
    public function create(array $data): int
    {
        $addressId = $data['address_id'] ?? null;
        
        $stmt = $this->db->prepare("
            INSERT INTO invoices 
            (created_by, client_id, address_id, invoice_number, invoice_date, due_date,
             subtotal, tax_type, tax_rate, tax_amount, discount, total_amount, status, notes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iiisssdsddddss",
            $data['created_by'],
            $data['client_id'],
            $addressId,
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

/**
 * Get daily totals broken down by status (total, paid, unpaid)
 * Paid = fully paid + partial paid amount
 * Unpaid = unpaid + partial remaining amount
 */
public function getDailyTotalsByStatus(string $startDate, string $endDate): array
{
    $stmt = $this->db->prepare("
        SELECT 
            DATE(invoice_date) AS date,
            SUM(total_amount) AS total,
            SUM(
                CASE 
                    WHEN LOWER(status) = 'paid' THEN total_amount 
                    WHEN LOWER(status) = 'partial' THEN COALESCE(amount_paid, 0)
                    ELSE 0 
                END
            ) AS paid,
            SUM(
                CASE 
                    WHEN LOWER(status) = 'unpaid' OR LOWER(status) = 'overdue' THEN total_amount 
                    WHEN LOWER(status) = 'partial' THEN (total_amount - COALESCE(amount_paid, 0))
                    ELSE 0 
                END
            ) AS unpaid
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
        $data[$row['date']] = [
            'total' => (float)$row['total'],
            'paid' => (float)$row['paid'],
            'unpaid' => (float)$row['unpaid']
        ];
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

public function getPaginated(int $limit, int $offset): array
{
    $stmt = $this->db->prepare(
        "SELECT * FROM invoices ORDER BY created_at DESC LIMIT ? OFFSET ?"
    );
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
public function getAllWithUsers(): array
{
    $userId = $_SESSION['user_id'];
    $userName = $_SESSION['name']; 
    $sql = "
        SELECT invoices.*
        FROM invoices
        WHERE invoices.created_by = ?
        ORDER BY invoices.created_at DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $invoices = [];
    while ($row = $result->fetch_assoc()) {
        $row['user_name'] = $userName; 
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
               ua.phone AS user_phone,
               CONCAT(ua.address, ' ', COALESCE(ua.pincode, '')) AS user_address,
               it.id AS item_id,
               it.item_name AS item_name,
               it.price,
               it.quantity
        FROM invoices i
        LEFT JOIN users u ON i.created_by = u.id
        LEFT JOIN user_addresses ua ON i.created_by = ua.user_id AND ua.is_default = 1
        LEFT JOIN invoice_items it ON i.id = it.invoice_id
        ORDER BY i.invoice_date DESC
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
                'invoice_date'=> $row['invoice_date'],
                'due_date'=> $row['due_date'],
                'subtotal'=> $row['subtotal'],
                'tax_rate'=> $row['tax_rate'],
                'tax_type'=> $row['tax_type'],
                'total_amount'=> $row['total_amount'],
                'status'=> $row['status'],
                'user_name' => $row['user_name'],
                'user_email'=> $row['user_email'],
                'user_phone'=> $row['user_phone'],
                'user_address'=> $row['user_address'],
                'items'=> []
            ];
        }

        if ($row['item_id']) {
            $invoices[$invoiceId]['items'][] = [
                'id'=> $row['item_id'],
                'name'=> $row['item_name'],
                'price'=> $row['price'],
                'quantity' => $row['quantity']
            ];
        }
    }

    return array_values($invoices);
}

// Dashboard Statistics Methods


public function getTotalAmount(): float
{
    $result = $this->db->query("SELECT COALESCE(SUM(total_amount), 0) AS total FROM invoices");
    return (float)$result->fetch_assoc()['total'];
}

/**
 * Get total amount of paid invoices
 */
public function getPaidAmount(): float
{
    $stmt = $this->db->prepare("SELECT COALESCE(SUM(total_amount), 0) AS total FROM invoices WHERE status = 'paid'");
    $stmt->execute();
    $result = $stmt->get_result();
    return (float)$result->fetch_assoc()['total'];
}

/**
 * Get total amount of unpaid invoices
 */
public function getUnpaidAmount(): float
{
    $stmt = $this->db->prepare("SELECT COALESCE(SUM(total_amount), 0) AS total FROM invoices WHERE status = 'unpaid'");
    $stmt->execute();
    $result = $stmt->get_result();
    return (float)$result->fetch_assoc()['total'];
}

/**
 * Get total amount of pending invoices
 */
public function getPendingAmount(): float
{
    $stmt = $this->db->prepare("SELECT COALESCE(SUM(total_amount), 0) AS total FROM invoices WHERE status = 'pending'");
    $stmt->execute();
    $result = $stmt->get_result();
    return (float)$result->fetch_assoc()['total'];
}

/**
 * Get total amount already paid for partial invoices
 */
public function getPartialPaidAmount(): float
{
    $stmt = $this->db->prepare("SELECT COALESCE(SUM(amount_paid), 0) AS total FROM invoices WHERE LOWER(status) = 'partial'");
    $stmt->execute();
    $result = $stmt->get_result();
    return (float)$result->fetch_assoc()['total'];
}

/**
 * Get total remaining amount for partial invoices
 */
public function getPartialRemainingAmount(): float
{
    $stmt = $this->db->prepare("SELECT COALESCE(SUM(total_amount - COALESCE(amount_paid, 0)), 0) AS total FROM invoices WHERE LOWER(status) = 'partial'");
    $stmt->execute();
    $result = $stmt->get_result();
    return (float)$result->fetch_assoc()['total'];
}

/**
 * Get total amount for partial invoices
 */
public function getPartialTotalAmount(): float
{
    $stmt = $this->db->prepare("SELECT COALESCE(SUM(total_amount), 0) AS total FROM invoices WHERE LOWER(status) = 'partial'");
    $stmt->execute();
    $result = $stmt->get_result();
    return (float)$result->fetch_assoc()['total'];
}

public function getCountByStatus(string $status): int
{
    $stmt = $this->db->prepare("SELECT COUNT(*) AS count FROM invoices WHERE status = ?");
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    return (int)$result->fetch_assoc()['count'];
}


public function updateOverdueStatuses(): int
{
    $today = date('Y-m-d');
    $stmt = $this->db->prepare("
        UPDATE invoices 
        SET status = 'overdue' 
        WHERE due_date < ? 
        AND LOWER(status) != 'paid'
        AND LOWER(status) != 'overdue'
    ");
    $stmt->bind_param("s", $today);
    $stmt->execute();
    return $stmt->affected_rows;
}

public function getDashboardStats(): array
{
    $paidAmount = $this->getPaidAmount();
    $unpaidAmount = $this->getUnpaidAmount();
    $partialPaidAmount = $this->getPartialPaidAmount();
    $partialRemainingAmount = $this->getPartialRemainingAmount();
    
    return [
        'total_amount' => $this->getTotalAmount(),
        'paid_amount' => $paidAmount,
        'unpaid_amount' => $unpaidAmount,
        'pending_amount' => $this->getPendingAmount(),
        'partial_paid_amount' => $partialPaidAmount,
        'partial_remaining_amount' => $partialRemainingAmount,
        'partial_total_amount' => $this->getPartialTotalAmount(),
        // Combined amounts: paid + partial received, unpaid + partial remaining
        'total_received' => $paidAmount + $partialPaidAmount,
        'total_outstanding' => $unpaidAmount + $partialRemainingAmount,
        'total_invoices' => $this->countAll(),
        'paid_count' => $this->getCountByStatus('paid'),
        'unpaid_count' => $this->getCountByStatus('unpaid'),
        'pending_count' => $this->getCountByStatus('pending'),
        'partial_count' => $this->getCountByStatus('partial')
    ];
}
public function getFilteredInvoices(array $filters = []): array
{
    $sql = "
        SELECT i.*, 
               u.name AS user_name,
               u.email AS user_email
        FROM invoices i
        LEFT JOIN users u ON i.created_by = u.id
        WHERE 1=1
    ";

    $params = [];
    $types = "";

    if (!empty($filters['invoice_number'])) {
        $sql .= " AND i.invoice_number LIKE ?";
        $params[] = "%" . $filters['invoice_number'] . "%";
        $types .= "s";
    }

    if (!empty($filters['email'])) {
        $sql .= " AND u.email LIKE ?";
        $params[] = "%" . $filters['email'] . "%";
        $types .= "s";
    }

    if (!empty($filters['status'])) {
        $sql .= " AND LOWER(i.status) = ?";
        $params[] = strtolower($filters['status']);
        $types .= "s";
    }

    $sql .= " ORDER BY i.invoice_date DESC";

    if (!empty($params)) {
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $result = $this->db->query($sql);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

public function updateStatus(int $id, string $status): bool
{
    $stmt = $this->db->prepare("UPDATE invoices SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    return $stmt->execute();
}


public function updateAmountPaid(int $id, float $amountPaid): bool
{
    $invoice = $this->getById($id);
    if (!$invoice) {
        return false;
    }
    $totalAmount = (float)$invoice['total_amount'];
    $dueAmount = max(0, $totalAmount - $amountPaid);
    
    $stmt = $this->db->prepare("UPDATE invoices SET amount_paid = ?, due_amount = ? WHERE id = ?");
    $stmt->bind_param("ddi", $amountPaid, $dueAmount, $id);
    return $stmt->execute();
}

public function updatePaymentStatus(int $id, float $amountPaid, string $status): bool
{
    $invoice = $this->getById($id);
    if (!$invoice) {
        return false;
    }
    $totalAmount = (float)$invoice['total_amount'];
    $dueAmount = max(0, $totalAmount - $amountPaid);
    
    // Auto-correct status based on actual payment
    if ($dueAmount <= 0) {
        $status = 'Paid';
    } elseif ($amountPaid > 0 && $dueAmount > 0) {
        $status = 'Partial';
    }
    
    $stmt = $this->db->prepare("UPDATE invoices SET amount_paid = ?, due_amount = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ddsi", $amountPaid, $dueAmount, $status, $id);
    return $stmt->execute();
}

public function getRemainingAmount(int $id): float
{
    $invoice = $this->getById($id);
    if (!$invoice) {
        return 0;
    }
    $amountPaid = (float)($invoice['amount_paid'] ?? 0);
    $totalAmount = (float)$invoice['total_amount'];
    return max(0, $totalAmount - $amountPaid);
}

public function fixIncorrectStatuses(): int
{
    // First, update all due_amounts based on total_amount - amount_paid
    $this->db->query("
        UPDATE invoices 
        SET due_amount = GREATEST(0, total_amount - amount_paid)
    ");
    
    // Fix invoices marked as 'Paid' but have due_amount > 0
    $stmt = $this->db->query("
        UPDATE invoices 
        SET status = 'Partial' 
        WHERE LOWER(status) = 'paid' 
        AND due_amount > 0
    ");
    $fixedPartial = $this->db->affected_rows;
    
    // Fix invoices with due_amount = 0 but not marked as Paid (and amount_paid > 0)
    $stmt = $this->db->query("
        UPDATE invoices 
        SET status = 'Paid' 
        WHERE due_amount <= 0 
        AND amount_paid > 0
        AND LOWER(status) != 'paid'
    ");
    $fixedPaid = $this->db->affected_rows;
    
    return $fixedPartial + $fixedPaid;
}

}
