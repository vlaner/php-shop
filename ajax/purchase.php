<?php
error_reporting(0);
session_start();
require '../utils/products.php';
require "../utils/adjustCartItems.php";

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

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {


    $summary = 0;
    foreach ($_SESSION['cart'] as $productValue) {
        if ($productValue['count'] == 0) {
            echo json_encode(-1);
            die();
        }
        $summary += $productValue['price'] * $productValue['count'];
    }

    if ($summary > $_SESSION['money']) {
        echo json_encode(-2);
        die();
    }

    $prodClass = new Products();
    $res = $prodClass->removeFromStock($_SESSION['cart']);

    if ($res == -1) {
        adjustCartItems();
        echo json_encode(-1);
        die();
    }

    $_SESSION['cart'] = [];
    $_SESSION['money'] -= $summary;

    echo json_encode(1);
} else {
    echo json_encode(-1);
}