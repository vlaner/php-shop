<?php
session_start();
require_once "./utils/products.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    $_SESSION['money'] = 100000;
}
$productsClass = new Products();
$_SESSION['offset'] = 0;

if (isset($_GET['q'])) {
    $get =  filter_var($_GET['q'], FILTER_SANITIZE_STRING);
    $products = $productsClass->getProductsByName($get, $_SESSION['offset']);
} else {
    $products = $productsClass->getProducts(10, $_SESSION['offset']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/styles/style.css">
    <title>SHOP</title>
    <script defer src="/static/js/ajax.js"></script>
</head>

<body>
    <header>

        <div class="header-top">+9 999 999 99 99</div>
        <div class="header-bottom">
            <form action="">
                <label>Search</label>
                <input type="search" name="q" id="search-item">
                <button type="submit">Search</button>
            </form>

            <a href="/">SHOP</a>
            <a href="cart/show.php">Cart</a>
        </div>
    </header>

    <div class="container">
        <?php
        $products =  json_decode($products, true);
        if (is_array($products)) {
            foreach ((array)$products as $productKey => $productValue) {
                $html = '';
                $title = $productValue['title'];
                $price = $productValue['price'];
                $photo = $productValue['photo'];
                $productId = $productValue['id'];
                $html .=
                    "
                <div class='card'>
    
                    <div class='card__photo'>
                        <img src={$photo}>
                    </div>
                
                    <a class='card__title' href='product/show.php?id={$productId}'>
                        <span>{$title}</span>
                    </a>
                    ";
                if (!array_key_exists($productId, $_SESSION['cart'])) {
                    $html .= "
                    <div class='card__price'>
                        {$price} ₽
                        <a class='cart-add' product-id={$productId}>To cart</a>
                    </div>
                    ";
                } else {
                    $html .= "
                    <div class='card__price'>
                        {$price} ₽
                        <a class='cart-add' product-id={$productId} href='/cart/show.php'>Added to cart</a>
                    </div>
                    ";
                }
                $html .= "
                </div>
                ";
                echo $html;
            }
        } else {
            echo 'No items found';
        }
        ?>

    </div>
    <div class="show-more">
        <?php
        if (!empty($_GET['q'])) {

            echo "<div hidden id='product-name'>{$_GET['q']}</div>";
        }
        ?>
        <button type="button" id="show-more">Show more</button>
    </div>
</body>

</html>