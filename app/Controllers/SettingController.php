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
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? null;

        if (!$name || !$email) {
            header("Location: /settings");
            exit;
        }

        $this->settingModel->updateUser($userId, $name, $email, $password);

        Session::set('name', $name);
        Session::set('email', $email);

        header("Location: /settings");
        exit;
    }
}
