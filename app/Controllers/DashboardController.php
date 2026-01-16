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

    /**
     * AJAX endpoint: Get chart data for a date range
     */
    public function getChartData()
    {
        $this->adminOnly();

        header('Content-Type: application/json');

        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-t');

        // Validate date format
        if (!strtotime($startDate) || !strtotime($endDate)) {
            echo json_encode(['error' => 'Invalid date format']);
            return;
        }

        $invoiceModel = new Invoice();

        // Get daily totals by status
        $dailyTotals = $invoiceModel->getDailyTotalsByStatus($startDate, $endDate);

        // Fill missing dates with 0
        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            (new \DateTime($endDate))->modify('+1 day')
        );

        $salesData = [];
        $totalAmount = 0;
        $paidAmount = 0;
        $unpaidAmount = 0;

        foreach ($period as $date) {
            $d = $date->format('Y-m-d');
            $dayTotal = $dailyTotals[$d]['total'] ?? 0;
            $dayPaid = $dailyTotals[$d]['paid'] ?? 0;
            $dayUnpaid = $dailyTotals[$d]['unpaid'] ?? 0;

            $salesData[] = [
                'date'   => $d,
                'total'  => $dayTotal,
                'paid'   => $dayPaid,
                'unpaid' => $dayUnpaid
            ];

            $totalAmount += $dayTotal;
            $paidAmount += $dayPaid;
            $unpaidAmount += $dayUnpaid;
        }

        echo json_encode([
            'success' => true,
            'salesData' => $salesData,
            'stats' => [
                'total_amount' => $totalAmount,
                'total_received' => $paidAmount,
                'total_outstanding' => $unpaidAmount
            ]
        ]);
    }
}
