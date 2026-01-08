<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Models\Setting;
use App\Models\Company;

class SettingController
{
    private Setting $settingModel;

    public function __construct()
    {
        $this->settingModel = new Setting();
    }

    public function index()
    {
        $userId = Session::get('user_id');
        $user = $this->settingModel->getUserById($userId);

        if (!$user) {
            header("Location: /login");
            exit;
        }

        if ($user['role'] !== 'admin') {

            require APP_ROOT . '/app/Views/user/setting.php';
        }
        else{
            // Fetch company data from database for admin
            $companyModel = new Company();
            $company = $companyModel->getFirst();
            
            require APP_ROOT . '/app/Views/admin/setting.php';

        }
    }
    public function company()
    {
        if (!Session::has('user_id') || Session::get('role') !== 'admin') {
            header("Location: /login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $logoName = null;

            if (!empty($_FILES['logo']['name'])) {
                $logoName = time() . '_' . $_FILES['logo']['name'];
                move_uploaded_file(
                    $_FILES['logo']['tmp_name'],
                    APP_ROOT . "/public/uploads/logos/" . $logoName
                );
            }

            $companyModel = new Company();

            $companyModel->save([
                'user_id'      => Session::get('user_id'),
                'company_name' => $_POST['company_name'],
                'email'        => $_POST['email'],
                'phone'        => $_POST['phone'],
                'address'      => $_POST['address'],
                'tax_number'   => $_POST['tax_number'] ?? '',
                'logo'         => $logoName
            ]);

            Session::set('success', 'Company details saved');
            header("Location: /products");
            exit;
        }

        require APP_ROOT . '/app/Views/admin/setting.php';
    }
    public function update()
    {
        $userId = Session::get('user_id');

        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phoneNo  = trim($_POST['phone'] ?? '');
        $address  = trim($_POST['address'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!$name || !$email) {
            header("Location: /settings");
            exit;
        }

        $this->settingModel->updateUser(
            $userId,
            $name,
            $email,
            $phoneNo,
            $address,
            $password ?: null
        );

        // Update session values
        Session::set('name', $name);
        Session::set('email', $email);
        Session::set('phone', $phoneNo);
        Session::set('address', $address);

        Session::set('success', 'Settings updated successfully!');

        header("Location: /products");
        exit;
    }
}
