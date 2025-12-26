<?php
namespace App\Models;

use App\Core\Model;

class Subscription extends Model
{
    // Get all plans
    public function getPlans(): array
    {
        $result = $this->db->query("SELECT * FROM subscription_plans ORDER BY price ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get a single plan by ID
    public function getPlanById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM subscription_plans WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->num_rows ? $res->fetch_assoc() : null;
    }

    // Subscribe user
    public function subscribe(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO subscriptions (user_id, plan_id, start_date, end_date, status, auto_renew)
            VALUES (?, ?, ?, ?, 'active', ?)
        ");

        $stmt->bind_param(
            "iissi",
            $data['user_id'],
            $data['plan_id'],
            $data['start_date'],
            $data['end_date'],
            $data['auto_renew']
        );

        return $stmt->execute();
    }

    // Get user's active subscription
    public function getUserSubscription(int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT s.*, p.plan_name, p.price, p.billing_cycle
            FROM subscriptions s
            JOIN subscription_plans p ON s.plan_id = p.id
            WHERE s.user_id = ? AND s.status = 'active'
            LIMIT 1
        ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->num_rows ? $res->fetch_assoc() : null;
    }
      public function getActiveSubscription(int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT 
                s.*,
                p.plan_name,
                p.price,
                p.billing_cycle
            FROM subscriptions s
            JOIN subscription_plans p ON p.id = s.plan_id
            WHERE s.user_id = ?
              AND s.start_date <= CURDATE()
              AND s.end_date >= CURDATE()
            ORDER BY s.end_date DESC
            LIMIT 1
        ");

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->num_rows
            ? $result->fetch_assoc()
            : null;
    }


}
