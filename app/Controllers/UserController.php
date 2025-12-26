
<?php
// namespace App\Controllers;

// use App\Models\User;
// use App\Helpers\Session;

// class UserController
// {
//     public function updateProfile()
//     {
//         if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//             echo json_encode(['success' => false, 'message' => 'Invalid request']);
//             return;
//         }

//         $id = Session::get('user_id');

//         $data = [
//             'name'    => trim($_POST['name']),
//             'email'   => trim($_POST['email']),
//             'address' => trim($_POST['address'])
//         ];

//         $userModel = new User();
//         $userModel->updateProfile($id, $data);

//         echo json_encode([
//             'success' => true,
//             'message' => 'Profile updated successfully'
//         ]);
//     }
// }
?>