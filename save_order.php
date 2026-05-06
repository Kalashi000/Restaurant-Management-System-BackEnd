<?php
include 'db_config.php';

$data = json_decode(file_get_contents("php://input"), true);

if(!$data){
    $data = $_POST;
}

if(isset($data['name'])){

$customer = mysqli_real_escape_string($conn,$data['name']);
$address = mysqli_real_escape_string($conn,$data['address']);
$contact = mysqli_real_escape_string($conn,$data['phone']);
$item = mysqli_real_escape_string($conn,$data['food']);
$payment = mysqli_real_escape_string($conn,$data['payment']);

$sql = "INSERT INTO orders (CUSTOMER, ADDRESS, CONTACT, ITEM, PAYMENT)
VALUES ('$customer','$address','$contact','$item','$payment')";

if(mysqli_query($conn,$sql)){
    echo json_encode(["status"=>"success"]);
}else{
    echo json_encode(["status"=>"error","message"=>mysqli_error($conn)]);
}

}else{
    echo json_encode(["status"=>"error","message"=>"No data received"]);
}
?>