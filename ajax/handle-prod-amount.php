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

if (isset($data['product_id']) && isset($data['action'])) {
    if (!array_key_exists($data['product_id'], $_SESSION['cart'])) {
        echo json_encode(-1);
        die();
    }

    $cartProduct = $_SESSION['cart'][$data['product_id']];
    $prod = new Products();

    $product = json_decode($prod->getProductById($data['product_id']), true);

    if ($data['action'] == 'increase') {
        if ($cartProduct['count'] + 1 > $product['quantity']) {
            $_SESSION['cart'][$data['product_id']]['count'] = $product['quantity'];
        } else {
            $_SESSION['cart'][$data['product_id']]['count']++;
        }
    }

    if ($data['action'] == 'decrease') {
        if ($cartProduct['count'] - 1 < 1) {
            $_SESSION['cart'][$data['product_id']]['count'] = 1;
        } else {
            $_SESSION['cart'][$data['product_id']]['count']--;
        }
    }

    $summary = 0;
    $overallItems = 0;

    foreach ($_SESSION['cart'] as $productKey => $productValue) {
        $summary += $productValue['price'] * $productValue['count'];
        $overallItems += $productValue['count'];
    }

    echo json_encode([
        'itemCount' => $_SESSION['cart'][$data['product_id']]['count'],
        'summary' => $summary,
        'overallItems' => $overallItems,
        'inStock' => $product['quantity']
    ]);
} else {
    echo json_encode(-1);
}