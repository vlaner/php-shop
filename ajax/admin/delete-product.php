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
header("Access-Control-Allow-Methods: DELETE");

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') die();

$body = file_get_contents('php://input');
$data = json_decode($body, true);

if (!isset($data['product_id'])) {
    echo json_encode(-1);
    die();
}

$prod = new Products();

$res = $prod->deleteProduct($data['product_id']);

echo $res;