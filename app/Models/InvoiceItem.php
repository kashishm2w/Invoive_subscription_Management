<?php

namespace App\Models;

use App\Core\Model;

class InvoiceItem extends Model
{
    public function addItem(int $invoiceId, array $item): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO invoice_items
            (invoice_id, item_name, quantity, price, total)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "isidd",
            $invoiceId,
            $item['name'],
            $item['quantity'],
            $item['price'],
            $item['total']
        );

        return $stmt->execute();
    }

    public function getByInvoice(int $invoiceId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
        $stmt->bind_param("i", $invoiceId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
