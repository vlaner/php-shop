<?php
session_start();
require_once '../utils/products.php';

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

$_SESSION['offset'] += 2;

$productsClass = new Products();
if (!isset($data['product_name'])) {
    $products = $productsClass->getProducts(2, $_SESSION['offset']);
} else {
    $prodName = $data['product_name'];
    $products = $productsClass->getProductsByName($prodName, $_SESSION['offset']);
}
echo json_encode($products);