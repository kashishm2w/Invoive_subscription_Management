<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Models\Invoice;

class DashboardController
{
    private Invoice $invoiceModel;

    public function __construct()
    {
        Session::start();
        $this->invoiceModel = new Invoice();
    }

    public function index()
    {
        $this->adminOnly();

        $invoiceModel = new Invoice();

        // Example: current month
        $startDate = date('Y-m-01'); // first day of month
        $endDate   = date('Y-m-t');  // last day of month

        // Get daily totals by status (total, paid, unpaid)
        $dailyTotals = $invoiceModel->getDailyTotalsByStatus($startDate, $endDate);

        // Fill missing dates with 0
        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            (new \DateTime($endDate))->modify('+1 day')
        );

        $salesData = [];
        foreach ($period as $date) {
            $d = $date->format('Y-m-d');
            $salesData[] = [
                'date'   => $d,
                'total'  => $dailyTotals[$d]['total'] ?? 0,
                'paid'   => $dailyTotals[$d]['paid'] ?? 0,
                'unpaid' => $dailyTotals[$d]['unpaid'] ?? 0
            ];
        }

        // Get dashboard statistics
        $stats = $invoiceModel->getDashboardStats();

        require APP_ROOT . '/app/Views/dashboard/index.php';
    }
    private function adminOnly()
    {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            header('Location: /home'); // or /login
            exit;
        }
    }
}
