<?php
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $food = mysqli_real_escape_string($conn, $_POST['food']);
    $payment = mysqli_real_escape_string($conn, $_POST['payment']);

    $sql = "INSERT INTO orders (customer_name, address, phone, food_item, payment_method) 
            VALUES ('$name', '$address', '$phone', '$food', '$payment')";

    if (mysqli_query($conn, $sql)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>