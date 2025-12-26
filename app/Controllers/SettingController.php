<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Models\Setting;

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

        if ($user['role'] === 'admin') {
            require APP_ROOT . '/app/Views/admin/setting.php';
        } else {
            require APP_ROOT . '/app/Views/user/setting.php';
        }
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

        Session::set('profile_updated', true);

        header("Location: /products");
        exit;
    }
}
