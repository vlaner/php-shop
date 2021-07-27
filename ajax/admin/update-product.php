<?php
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
if (!isset($_POST['id'])) {
    $err[] = 'id is required';
}


$prod = new Products();

$product = json_decode($prod->getProductById($_POST['id']), true);
unset($product['quantity']);


$diff = array_diff($_POST, $product);

if (empty($diff)) {
    $err[] = 'No fields changed';
}
if ($err) {
    echo json_encode(['errors' => $err]);
    die();
}

$diff['id'] = $_POST['id'];

if (array_key_exists('photo', $_POST)) {
    unset($product['photo']);
}
$res = $prod->updateProduct(...$diff);

echo ($res);