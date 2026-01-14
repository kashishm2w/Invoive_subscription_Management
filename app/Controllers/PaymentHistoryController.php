<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Models\Payment;

class PaymentHistoryController
{
    private Payment $paymentModel;

    public function __construct()
    {
        Session::start();
        $this->paymentModel = new Payment();
    }

    /*Show users payment history*/
    public function index()
    {
        if (!Session::has('user_id')) {
            header("Location: /login");
            exit;
        }

        $userId = Session::get('user_id');
        $currentPage = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($currentPage - 1) * $limit;
        $payments = $this->paymentModel->getPaginatedByUser($userId, $limit, $offset);
        $totalItems = $this->paymentModel->countByUser($userId);

        $pagination = [
            'total' => $totalItems,
            'per_page' => $limit,
            'current_page' => $currentPage,
            'total_pages' => ceil($totalItems / $limit),
        ];

        require APP_ROOT . '/app/Views/user/payment_history.php';
    }

    /*Admin: View all payment*/
    public function adminIndex()
    {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            header("Location: /login");
            exit;
        }

        $currentPage = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($currentPage - 1) * $limit;

        $payments = $this->paymentModel->getPaginated($limit, $offset);
        $totalItems = $this->paymentModel->countAll();

        $pagination = [
            'total' => $totalItems,
            'per_page' => $limit,
            'current_page' => $currentPage,
            'total_pages' => ceil($totalItems / $limit),
        ];

        require APP_ROOT . '/app/Views/admin/payment_history.php';
    }

    public function fetchPaymentsAjax()
    {
        if (!Session::has('user_id')) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $userId = Session::get('user_id');
        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $payments = $this->paymentModel->getPaginatedByUser($userId, $limit, $offset);
        $totalItems = $this->paymentModel->countByUser($userId);

        $pagination = [
            'total' => $totalItems,
            'per_page' => $limit,
            'current_page' => $page,
            'total_pages' => max(1, ceil($totalItems / $limit)),
        ];

        header('Content-Type: application/json');
        echo json_encode([
            'payments' => $payments,
            'pagination' => $pagination
        ]);
    }
    public function fetchPaymentsAdminAjax()
    {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $payments = $this->paymentModel->getPaginated($limit, $offset);
        $totalItems = $this->paymentModel->countAll();

        $pagination = [
            'total' => $totalItems,
            'per_page' => $limit,
            'current_page' => $page,
            'total_pages' => max(1, ceil($totalItems / $limit)),
        ];

        header('Content-Type: application/json');
        echo json_encode([
            'payments' => $payments,
            'pagination' => $pagination
        ]);
    }
}
