<?php
session_start();
require_once "products.php";

function adjustCartItems()
{
    $prodClass = new Products();
    $itemsInfo = json_decode($prodClass->getProductsById(array_keys($_SESSION['cart'])), true);

    foreach ($itemsInfo as $itemInfo) {
        $_SESSION['cart'][$itemInfo['id']]['quantity'] = $itemInfo['quantity'];
        $_SESSION['cart'][$itemInfo['id']]['count'] = $itemInfo['quantity'];
    }
}