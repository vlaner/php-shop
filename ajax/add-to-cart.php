<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    $_SESSION['money'] = 100000;
}
require_once '../utils/products.php';

header("Access-Control-Allow-Origin: same-site");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') die();

$body = file_get_contents('php://input');
$data = json_decode($body, true);

if (filter_var($data['product_id'], FILTER_VALIDATE_INT)) {

    $product = new Products();
    $res = $product->getProductById($data['product_id']);

    $prod = json_decode($res, true);

    if ($prod === -1) {
        echo json_encode(-1);
        die();
    }

    if (!array_key_exists($data['product_id'], $_SESSION['cart'])) {
        $_SESSION['cart'][$data['product_id']] = [
            'count' => 1,
            'title' => $prod['title'],
            'price' => $prod['price'],
            'photo' => $prod['photo'],
            'quantity' => $prod['quantity']
        ];
    }
    echo json_encode($data['product_id']);
} else {
    echo json_encode(-1);
}