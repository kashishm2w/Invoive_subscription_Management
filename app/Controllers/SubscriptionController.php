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
    if (!Session::has('user_id')) {
        header('Location: /login');
        exit;
    }

    $userId = Session::get('user_id');
    $planId = $_POST['plan_id'] ?? null;
    $autoRenew = isset($_POST['auto_renew']) ? 1 : 0;

    if (!$planId) {
        Session::set('error', 'Please select a plan');
        header('Location: /subscriptions');
        exit;
    }

    $activeSubscription = $this->subscriptionModel
        ->getActiveSubscription($userId);

    if ($activeSubscription) {
        Session::set(
            'error',
            'You already have an active plan: ' . $activeSubscription['plan_name']
        );

        header('Location: /subscriptions');
        exit;
    }

    // Redirect to payment page
    header('Location: /payment?plan_id=' . $planId . '&auto_renew=' . $autoRenew);
    exit;
}

/**
 * Admin: Track all subscriptions
 */
public function trackSubscriptions()
{
    Session::start();
    
    if (!Session::has('user_id') || Session::get('role') !== 'admin') {
        header('Location: /login');
        exit;
    }

    $currentPage = (int)($_GET['page'] ?? 1);
    $limit = 10;
    $offset = ($currentPage - 1) * $limit;

    $allSubscriptions = $this->subscriptionModel->getAllWithUserDetails();
    $totalItems = count($allSubscriptions);
    
    $subscriptions = array_slice($allSubscriptions, $offset, $limit);

    $pagination = [
        'total'        => $totalItems,
        'per_page'     => $limit,
        'current_page' => $currentPage,
        'total_pages'  => ceil($totalItems / $limit),
    ];

    require APP_ROOT . '/app/Views/admin/track_subscriptions.php';
}

}
