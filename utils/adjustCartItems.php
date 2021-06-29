<?php
session_start();
require_once "products.php";

function adjustCartItems()
{
    $prodClass = new Products();
    $itemsInfo = json_decode($prodClass->getProductsById(array_keys($_SESSION['cart'])), true);

    foreach ($itemsInfo as $arrayIndex => $itemInfo) {
        if ($itemInfo['quantity'] < 1) {
            continue;
        }

        $_SESSION['cart'][$itemInfo['id']]['quantity'] = $itemInfo['quantity'];
        $_SESSION['cart'][$itemInfo['id']]['count'] = $itemInfo['quantity'];
    }
    $_SESSION['msg'][] = 'Check products stock';
}