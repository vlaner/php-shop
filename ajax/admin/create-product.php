<?php
error_reporting(0);
session_start();

if (!isset($_SESSION['logged_in'])) {
    echo json_encode(-1);
    die();
}

require_once "../../utils/products.php";

header("Access-Control-Allow-Origin: same-site");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') die();

$targetDir = '/static/photos/';
$extensions = ['jpg', 'jpeg', 'png'];
$maxFileSize = 2 * 1024 * 1024; // 2 MB
$err = [];

if (!isset($_POST['title'])) {
    $err[] = 'Title is required';
}
if (!isset($_POST['description'])) {
    $err[] = 'Description is required';
}
if (!isset($_POST['price'])) {
    $err[] = 'Price is required';
}
if (!isset($_FILES['photo'])) {
    $err[] = 'Photo is required';
}

$temp = explode('/', $_FILES['photo']['type']);
$filetype = end($temp);

if (!in_array($filetype, $extensions)) {
    $err[] = 'Filetype must be ' . implode(', ', $extensions);
}
if ($_FILES['photo']['size'] > $maxFileSize) {
    $err[] = 'Maximum file size ' . $maxFileSize;
}

if ($err) {
    echo json_encode(['errors' => $err]);
    die();
}

$newName = bin2hex(random_bytes(15)) . ".{$filetype}";
$filePath = $targetDir . $newName;

$prod = new Products();

if (move_uploaded_file($_FILES['photo']['tmp_name'], "../.." . $filePath)) {
    $res =  $prod->createProduct($_POST['title'], $_POST['description'], $_POST['price'], $filePath);
} else {
    $res =  $prod->createProduct($_POST['title'], $_POST['description'], $_POST['price'], ".." . $targetDir . "default.jpg");
    $_SESSION['msg'] = 'Could not upload image, using placeholder image';
}
echo json_encode($res);