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
            'description' => $_POST['description']
        ];

        if($id) {
            $this->planModel->update($id, $data);
        } else {
            $this->planModel->create($data);
        }

        header('Location: /subscriptions');
        exit;
    }

    // Delete plan
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if($id) $this->planModel->delete($id);

        header('Location: /subscriptions');
        exit;
    }
}
