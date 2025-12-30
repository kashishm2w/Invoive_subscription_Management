<?php
require 'config.php';

if ($_POST) {
    $token  = $_POST['stripeToken'];
    $name   = $_POST['name'];
    $amount = $_POST['amount'] * 100; // Stripe uses paise

    try {
        $charge = \Stripe\Charge::create([
            "amount" => $amount,
            "currency" => "inr",
            "description" => "Test Payment",
            "source" => $token,
            "metadata" => [
                "customer_name" => $name
            ]
        ]);

        header("Location: success.php");
    } catch(Exception $e) {
        echo $e->getMessage();
    }
}
?>
