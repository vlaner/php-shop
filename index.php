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
    $products = $productsClass->getProducts(2, $_SESSION['offset']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="/static/styles.css">
    <title>SHOP</title>
    <script defer src="/static/js/index.js"></script>
</head>

<body>
    <header class="p-3 bg-dark text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                        <use xlink:href="#bootstrap"></use>
                    </svg>
                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="/" class="nav-link px-2 text-white">Home</a></li>
                    <li><a href="/" class="nav-link px-2 text-white">+9 999 999 99 99</a></li>
                </ul>

                <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" action="/">
                    <div class="input-group">
                        <input type="search" name="q" id="search-item" class="form-control form-control-dark" placeholder="Search...">
                        <button type="submit" class="btn btn-secondary">Search</button>
                    </div>
                </form>

                <div class="text-end">
                    <a class="btn btn-outline-light me-2" href="/admin/login.php">Admin login</a>
                    <a class="btn btn-outline-light me-2" href="/cart/show.php">Cart</a>
                </div>
            </div>
        </div>
    </header>



    <div class="container">
        <div class="row">
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
                    <div class='col-md-4'>
                        <a href='product/show.php?id={$productId}'>
                            <img src={$photo}>
                        </a>
                        </br>
                        <a class='link-dark' href='product/show.php?id={$productId}'>
                            <span>{$title}</span>
                        </a>
                        ";
                    if (!array_key_exists($productId, $_SESSION['cart'])) {
                        $html .= "
                        <div>
                            {$price} ₽
                            <a class='cart-add btn btn-outline-dark me-2' product-id={$productId}>To cart</a>
                        </div>
                        ";
                    } else {
                        $html .= "
                        <div>
                            {$price} ₽
                            <a class='cart-add btn btn-outline-dark me-2' product-id={$productId} href='/cart/show.php'>Added to cart</a>
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
    </div>
    <div class="show-more">
        <?php
        if (!empty($_GET['q'])) {

            echo "<div hidden id='product-name'>{$_GET['q']}</div>";
        }
        ?>
        <div class="mt-5 d-flex align-items-center justify-content-center">
            <button type="button" class='text-center btn btn-outline-dark me-2' id="show-more">Show more</button>
        </div>
    </div>
</body>

</html>