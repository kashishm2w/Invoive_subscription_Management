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
        } else {
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
                'user_id' => Session::get('user_id'),
                'company_name' => $_POST['company_name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'tax_number' => $_POST['tax_number'] ?? '',
                'logo' => $logoName
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
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Name is required';
        } elseif (strlen($name) > 50) {
            $errors['name'] = 'Name must not exceed 50 characters';
        } elseif (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
            $errors['name'] = 'Name can contain only letters and spaces';
        }

        if ($email === '') {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email address';
        }

        // Only validate password if it's provided
        if ($password !== '') {
            if (strlen($password) < 8) {
                $errors['password'] = 'Password must be at least 8 characters';
            } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
                $errors['password'] = 'Password must have at least 8 characters including uppercase, lowercase, and number.';
            }
        }

        if (!empty($errors)) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'errors' => $errors]);
                exit;
            }
            Session::set('errors', $errors);
            Session::set('old', $_POST);
            header("Location: /settings");
            exit;
        }

        try {
            $this->settingModel->updateUser(
                $userId,
                $name,
                $email,
                $password ?: null
            );

            // Update session values
            Session::set('name', $name);
            Session::set('email', $email);

            if ($isAjax) {
                header('Content-Type: application/json');
                $message = $password !== '' ? 'Password and settings updated successfully!' : 'Settings updated successfully!';
                echo json_encode(['success' => true, 'message' => $message]);
                exit;
            }

            Session::set('success', 'Settings updated successfully!');
            header("Location: /products");
            exit;
        } catch (\Exception $e) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'errors' => ['general' => 'An error occurred while updating settings. Please try again.']]);
                exit;
            }
            Session::set('errors', ['general' => 'An error occurred while updating settings.']);
            header("Location: /settings");
            exit;
        }
    }
}
