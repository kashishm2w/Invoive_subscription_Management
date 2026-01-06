<?php
namespace App\Controllers;

use App\Models\SubscriptionPlan;
use App\Helpers\Session;

class SubscriptionPlanController
{
    private SubscriptionPlan $planModel;

    public function __construct()
    {
        Session::start();
        if(Session::get('role') !== 'admin') {
            die('Access Denied'); // only admin can manage plans
        }

        $this->planModel = new SubscriptionPlan();
    }

    // List all plans (for admin)
    public function index()
    {
        $plans = $this->planModel->getAll();
        require APP_ROOT . '/app/Views/subscription/subscription.php';
    }

    // Fetch plan details (AJAX for edit modal)
    public function getPlan()
    {
        $id = $_GET['id'] ?? null;
        if(!$id) die(json_encode(['error' => 'Plan ID missing']));
        $plan = $this->planModel->getPlan($id);
        header('Content-Type: application/json');
        echo json_encode($plan);
    }

    // Save plan (add or edit)
    public function save()
    {
        $id = $_POST['plan_id'] ?? null;
        $data = [
            'plan_name' => $_POST['plan_name'],
            'price' => $_POST['price'],
            'billing_cycle' => $_POST['billing_cycle'],
            'description' => $_POST['description'],
            'discount_percent'=>$_POST['discount_percent']
        ];

        if($id) {
            $this->planModel->update($id, $data);
            Session::set('success', 'Plan updated successfully!');
        } else {
            $this->planModel->create($data);
            Session::set('success', 'Plan added successfully!');
        }

        header('Location: /subscriptions');
        exit;
    }

    // Delete plan
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            Session::set('error', 'Plan ID is required');
            header('Location: /subscriptions');
            exit;
        }

        // Check if plan exists
        $plan = $this->planModel->getPlan($id);
        if (!$plan) {
            Session::set('error', 'Plan not found');
            header('Location: /subscriptions');
            exit;
        }

        // Check if plan has any subscriptions
        if ($this->planModel->hasSubscriptions($id)) {
            Session::set('error', 'Cannot delete this plan because it has active subscriptions. Please cancel or reassign those subscriptions first.');
            header('Location: /subscriptions');
            exit;
        }

        // Try to delete the plan
        try {
            $result = $this->planModel->delete($id);
            if ($result) {
                Session::set('success', 'Plan "' . htmlspecialchars($plan['plan_name']) . '" deleted successfully');
            } else {
                Session::set('error', 'Failed to delete plan.');
            }
        } catch (\mysqli_sql_exception $e) {
            Session::set('error', 'Cannot delete plan: It has associated subscriptions.');
        } catch (\Exception $e) {
            Session::set('error', 'An error occurred while deleting the plan.');
        }

        header('Location: /subscriptions');
        exit;
    }
}
