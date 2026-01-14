<?php

namespace App\Controllers;

use App\Helpers\Session;
use App\Models\UserAddress;

class AddressController
{
    private UserAddress $addressModel;

    public function __construct()
    {
        $this->addressModel = new UserAddress();
        Session::start();
    }

    /**
     * Get all addresses for the logged-in user (AJAX)
     */
    public function getAddresses()
    {
        header('Content-Type: application/json');

        if (!Session::has('user_id')) {
            echo json_encode(['success' => false, 'error' => 'Not logged in']);
            exit;
        }

        $userId = Session::get('user_id');
        $addresses = $this->addressModel->getByUserId($userId);

        echo json_encode([
            'success' => true,
            'addresses' => $addresses
        ]);
        exit;
    }

    /**
     * Add a new address (AJAX POST)
     */
    public function addAddress()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            exit;
        }

        if (!Session::has('user_id')) {
            echo json_encode(['success' => false, 'error' => 'Not logged in']);
            exit;
        }

        $userId = Session::get('user_id');

        // Validate required fields
        $requiredFields = ['full_name', 'phone', 'address', 'city', 'state', 'pincode'];
        $errors = [];

        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
            }
        }
        if (
            !empty($_POST['full_name']) &&
            !preg_match('/^[a-zA-Z]+(?:\s[a-zA-Z]+)*$/', $_POST['full_name'])
        ) {
            $errors['full_name'] = 'Invalid Name';
        }

        // Validate phone number (10 digits)
        if (!empty($_POST['phone']) && !preg_match('/^[0-9]{10}$/', $_POST['phone'])) {
            $errors['phone'] = 'Phone must be 10 digits';
        }
        if (!empty($_POST['city']) && !preg_match('/^[a-zA-Z]{2,100}$/', $_POST['city'])) {
            $errors['city'] = 'Invalid city';
        }
        if (!empty($_POST['state']) && !preg_match('/^[a-zA-Z]{2,100}$/', $_POST['state'])) {
            $errors['state'] = 'Invalid state';
        }

        // Validate pincode (6 digits)
        if (!empty($_POST['pincode']) && !preg_match('/^[0-9]{6}$/', $_POST['pincode'])) {
            $errors['pincode'] = 'Pincode must be 6 digits';
        }

        if (!empty($errors)) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            exit;
        }

        // Create address
        $addressId = $this->addressModel->create([
            'user_id' => $userId,
            'full_name' => trim($_POST['full_name']),
            'phone' => trim($_POST['phone']),
            'address' => trim($_POST['address']),
            'city' => trim($_POST['city']),
            'state' => trim($_POST['state']),
            'pincode' => trim($_POST['pincode']),
            'is_default' => !empty($_POST['is_default']) ? 1 : 0
        ]);

        if ($addressId) {
            $address = $this->addressModel->getById($addressId);
            echo json_encode([
                'success' => true,
                'message' => 'Address added successfully',
                'address' => $address
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to add address']);
        }
        exit;
    }

    /* Set an address as default */
    public function setDefaultAddress()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            exit;
        }

        if (!Session::has('user_id')) {
            echo json_encode(['success' => false, 'error' => 'Not logged in']);
            exit;
        }

        $addressId = $_POST['address_id'] ?? null;
        if (!$addressId) {
            echo json_encode(['success' => false, 'error' => 'Address ID is required']);
            exit;
        }

        $userId = Session::get('user_id');
        $result = $this->addressModel->setDefault((int)$addressId, $userId);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Default address updated']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update default address']);
        }
        exit;
    }
}
