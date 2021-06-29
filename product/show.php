<?php
session_start();
require_once "../utils/products.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    $_SESSION['money'] = 100000;
}
$productsClass = new Products();
$_SESSION['offset'] = 0;

if (!empty($_GET['id'])) {
    if (!filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
        header("Location: /index.php");
    }
    $products = $productsClass->getProductById($_GET['id']);
} else {
    header("Location: /index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/static/styles/showProduct.css">
    <title>SHOP</title>
    <script defer src="/static/js/ajax.js"></script>
</head>

<body>
    <header>

        <div class="header-top">+9 999 999 99 99</div>
        <div class="header-bottom">
            <form action="/index.php">
                <label>Search</label>
                <input type="search" name="q" id="search-item">
                <button type="submit">Search</button>
            </form>

            <a href="/">SHOP</a>
            <a href="/cart/show.php">Cart</a>
        </div>
    </header>

    <div class="container">
        <?php
        $products = json_decode($products, true);

        if ($products !== -1) {
            $html = '';
            $productId = $products['id'];
            $title = $products['title'];
            $description = $products['description'];
            $price = $products['price'];
            $photo = $products['photo'];
            $quantity = $products['quantity'];

            $html .= "
            <div>
            <span>{$title}</span>
                <img src={$photo}>
            </div>
           
            <div>
                <span class='description'>{$description}</span>
            </div> 
            ";
            if (!array_key_exists($productId, $_SESSION['cart'])) {
                $html .= "
                <div>
                    {$price} ₽
                    <a class='cart-add' product-id={$productId}>To cart</a>
                </div>
                ";
            } else {
                $html .= "
                <div>
                    {$price} ₽
                    <a class='cart-add' href='/cart/show.php' product-id={$productId}>Added to cart</a>
                </div>
                ";
            }
            echo $html;
        } else {
            echo 'No product found';
        }

        ?>

    </div>

</body>

</html>