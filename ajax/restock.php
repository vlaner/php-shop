<?php
session_start();
require_once "../utils/products.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    $_SESSION['money'] = 100000;
}

header("Access-Control-Allow-Origin: same-site");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') die();

$body = file_get_contents('php://input');
$data = json_decode($body, true);

if (isset($data['product_id'])) {
    if (!array_key_exists($data['product_id'], $_SESSION['cart'])) {
        echo json_encode(-1);
        die();
    }

    $prod = new Products();
    $prod->restock($data['product_id']);
    echo json_encode(1);
} else {
    echo json_encode(-1);
}