<?php
session_start();
require_once "../../utils/products.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    $_SESSION['money'] = 100000;
}
if (!isset($_SESSION['logged_in'])) {
    echo json_encode(-1);
    die();
}
header("Access-Control-Allow-Origin: same-site");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') die();
$body = file_get_contents('php://input');
$data = json_decode($body, true);

if (!isset($data['product_id'])) {
    echo json_encode(-1);
    die();
}
$prod = new Products();
$prod->restock($data['product_id']);
echo json_encode(1);