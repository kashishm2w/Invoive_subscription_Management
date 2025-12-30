<?php
namespace App\Models;

use App\Core\Model;

class SubscriptionPlan extends Model
{
    public function getAll(): array
    {
        $result = $this->db->query("SELECT * FROM subscription_plans ORDER BY price ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPlan(int $id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM subscription_plans WHERE id=? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create(array $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO subscription_plans (plan_name, price, billing_cycle, description)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("sdss", $data['plan_name'], $data['price'], $data['billing_cycle'], $data['description']);
        return $stmt->execute();
    }

    public function update(int $id, array $data)
    {
        $stmt = $this->db->prepare("
            UPDATE subscription_plans
            SET plan_name=?, price=?, billing_cycle=?, description=?
            WHERE id=?
        ");
        $stmt->bind_param("sdssi", $data['plan_name'], $data['price'], $data['billing_cycle'], $data['description'], $id);
        return $stmt->execute();
    }

    public function delete(int $id)
    {
        $stmt = $this->db->prepare("DELETE FROM subscription_plans WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Check if a plan has any subscriptions (active or not)
     */
    public function hasSubscriptions(int $id): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM subscriptions WHERE plan_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] > 0;
    }
}
