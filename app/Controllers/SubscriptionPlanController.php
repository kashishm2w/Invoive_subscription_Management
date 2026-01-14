<?php

namespace App\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Helpers\Session;

class SubscriptionPlanController
{
    private SubscriptionPlan $planModel;
    private Subscription $subscriptionModel;
    public function __construct()
    {
        Session::start();
        if (Session::get('role') !== 'admin') {
            die('Access Denied'); // only admin can manage plans
        }

        $this->planModel = new SubscriptionPlan();
        $this->subscriptionModel = new Subscription();
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
        if (!$id) die(json_encode(['error' => 'Plan ID missing']));
        $plan = $this->planModel->getPlan($id);
        header('Content-Type: application/json');
        echo json_encode($plan);
    }

    // Save plan (add or edit)
    public function save()
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        $id = $_POST['plan_id'] ?? null;
        // Trim inputs
        $planName = trim($_POST['plan_name'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $billingCycle = trim($_POST['billing_cycle'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $discount = trim($_POST['discount_percent'] ?? '');

        // Helper function for error response
        $sendError = function($message) use ($isAjax) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => $message]);
                exit;
            }
            Session::set('error', $message);
            header('Location: /subscriptions');
            exit;
        };

        //  validation
        if (empty($planName)) {
            $sendError('Plan name is required');
        }

        if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\s\-()]{2,100}$/', $planName)) {
            $sendError('Plan name contains invalid characters');
        }

        // Price must be positive number
        if (!is_numeric($price) || $price <= 0) {
            $sendError('Price must be a valid number greater than 0');
        }

        // Billing cycle allow only fixed values
        $allowedCycles = ['monthly', 'yearly'];
        if (!in_array($billingCycle, $allowedCycles, true)) {
            $sendError('Invalid billing cycle selected');
        }

        // Discount 0–100
        if ($discount !== '' && (!is_numeric($discount) || $discount < 0 || $discount > 100)) {
            $sendError('Discount must be between 0 and 100');
        }

        // Description length 
        if (!empty($description) && strlen($description) > 500) {
            $sendError('Description must be less than 500 characters');
        }
        
        // Check for duplicate plan name
        $excludeId = $id ? (int)$id : null;
        if ($this->planModel->existsByName($planName, $excludeId)) {
            $sendError('A plan with the name "' . htmlspecialchars($planName) . '" already exists. Please choose a different name.');
        }

        $data = [
            'plan_name' => $planName,
            'price' => $_POST['price'],
            'billing_cycle' => $_POST['billing_cycle'],
            'description' => $_POST['description'],
            'discount_percent' => $_POST['discount_percent']
        ];

        $successMessage = $id ? 'Plan updated successfully!' : 'Plan added successfully!';
        
        if ($id) {
            $this->planModel->update($id, $data);
        } else {
            $this->planModel->create($data);
        }

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => $successMessage]);
            exit;
        }

        Session::set('success', $successMessage);
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
    public function cancel()
    {
        if (!Session::has('user_id')) {
            header('Location: /login');
            exit;
        }

        $userId = Session::get('user_id');
        $this->subscriptionModel->cancelByUser($userId);

        Session::set('success', 'Subscription cancelled successfully');
        header('Location: /subscriptions');
    }
}
