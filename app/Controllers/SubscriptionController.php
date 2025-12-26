<?php
namespace App\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Helpers\Session;

class SubscriptionController
{
    private SubscriptionPlan $planModel;
    private Subscription $subscriptionModel;

    public function __construct()
    {
        Session::start();
        $this->planModel = new SubscriptionPlan();
        $this->subscriptionModel = new Subscription();
    }

public function index()
{
    $plans = $this->planModel->getAll();

    $currentSubscription = null;

    if (Session::has('user_id')) {
        $currentSubscription = $this->subscriptionModel
            ->getActiveSubscription(Session::get('user_id'));
    }

    require APP_ROOT . '/app/Views/subscription/subscription.php';
}

    public function subscribe()
    {
        if(!Session::has('user_id')){
            header('Location: /login');
            exit;
        }

        $userId = Session::get('user_id');
        $planId = $_POST['plan_id'];
        $start = date('Y-m-d');
        $end   = date('Y-m-d', strtotime('+1 month'));

        $this->subscriptionModel->subscribe([
            'user_id' => $userId,
            'plan_id' => $planId,
            'start_date' => $start,
            'end_date' => $end,
            'auto_renew' => isset($_POST['auto_renew']) ? 1 : 0
        ]);

        header('Location: /subscriptions');
        exit;
    }
}
