<?php
session_start();

require_once "../utils/database.php";

header("Access-Control-Allow-Origin: same-site");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') die();

$body = file_get_contents('php://input');
$data = json_decode($body, true);

if (isset($data['email']) and isset($data['password'])) {
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(-2);
        die();
    }
    $database = new Database();

    $adminData = $database->getAdmin($data['email'], $data['password']);

    echo json_encode($adminData);

    if ($adminData == 1) {
        $_SESSION['logged_in'] = 1;
    }
} else {
    echo json_encode(-1);
    die();
}
