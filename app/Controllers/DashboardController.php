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

    $invoiceModel = new \App\Models\Invoice();

    // Example: current month
    $startDate = date('Y-m-01'); // first day of month
    $endDate   = date('Y-m-t');  // last day of month

    $dailyTotals = $invoiceModel->getDailyTotals($startDate, $endDate);

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
            'date'  => $d,
            'total' => $dailyTotals[$d] ?? 0
        ];
    }

    require APP_ROOT . '/app/Views/dashboard/index.php';
}
private function adminOnly()
    {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            header('Location: /products'); // or /login
            exit;
        }
    }

} 
