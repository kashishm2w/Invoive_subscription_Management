<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Models\User;

class AuthController
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
        Session::start(); // start session at the top
    }
    /*  REGISTER  */

    // GET /register
    public function showRegister()
    {
        $errors = [];
        require APP_ROOT . '/app/Views/auth/register.php';
    }

    // POST /register
    public function register()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name     = trim($_POST['name'] ?? '');
            $email    = strtolower(trim($_POST['email'] ?? ''));
            $password = trim($_POST['password'] ?? '');
            $confirm  = trim($_POST['confirm_password'] ?? '');

            // Validation
            if ($name === '' || $email === '' || $password === '' || $confirm === '') {
                $errors[] = "All fields are required.";
            }
            // Name
            elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
                $errors[] = "Name can only contain letters and spaces.";
            } elseif (strlen($name) < 2 || strlen($name) > 50) {
                $errors[] = "Name should contain 2 to 50 characters.";
            }
            // Email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            } elseif (strlen($email) > 100) {
                $errors[] = "Invalid Emial format.";
            } elseif ($this->user->emailExists($email)) {
                $errors[] = "Email already registered.";
            }
            // Paassword
            if ($password !== $confirm) {
                $errors[] = "Passwords do not match.";
            } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
                $errors[] = "Password must have at least 8 characters including uppercase, lowercase, and number.";
            }

            if (!empty($errors)) {
                require APP_ROOT . '/app/Views/auth/register.php';
                return;
            }

            // Hash password securely
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Save to database
            $this->user->register($name, $email, $hashed);

            Session::set('success', 'Registration successful! Please login.');
            header("Location: /login");
            exit;
        }
    }
    /*  LOGIN  */

    // GET /login
    public function showLogin()
    {
        // If already logged in
        if (Session::has('user_id')) {

            // Admin → dashboard
            if (Session::get('role') === 'admin') {
                header("Location: /dashboard");
                exit;
            }

            // Normal user → products
            header("Location: /home");
            exit;
        }

        // Not logged in → show login page
        require APP_ROOT . '/app/Views/auth/login.php';
    }

    // POST /login
    public function login()
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = strtolower(trim($_POST['email'] ?? ''));
            $password = trim($_POST['password'] ?? '');

            if ($email === '' || $password === '') {
                $errors[] = "Email and password are required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            } else {
                $user = $this->user->findByEmail($email);
                if (!$user || !password_verify($password, $user['password'])) {
                    $errors[] = "Invalid email or password.";
                }
            }

            if (!empty($errors)) {
                require APP_ROOT . '/app/Views/auth/login.php';
                return;
            }

            // LOGIN SUCCESS
            Session::set('user_id', $user['id']);
            Session::set('name', $user['name']);
            Session::set('role', $user['role']);
            Session::set('email', $user['email']);
            Session::set('success', 'Welcome back, ' . $user['name'] . '!');


            // Popup login redirect (cart → checkout)
            if (!empty($_POST['redirect_after_login'])) {
                header("Location: " . $_POST['redirect_after_login']);
                exit;
            }

            // Normal redirect
            if (Session::has('redirect_after_login')) {
                $redirect = Session::get('redirect_after_login');
                Session::remove('redirect_after_login');
                header("Location: " . $redirect);
                exit;
            }

            // Default redirect based on role
            if (Session::get('role') === 'admin') {
                header("Location: /dashboard");
            } else {
                header("Location: /home");
            }
            exit;
        }

        // GET request
        require APP_ROOT . '/app/Views/auth/login.php';
    }

    public function logout()
    {
        // Start session if not started
        Session::start();

        // Destroy all session data
        Session::destroy();

        // Redirect to login page
        header("Location: /dashboard");
        exit;
    }
}
