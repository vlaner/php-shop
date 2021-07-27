<?php
session_start();
require_once "../utils/products.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    $_SESSION['money'] = 100000;
}
$_SESSION['offset'] = 0;

if (!empty($_GET['id'])) {
    if (!filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
        header("Location: /index.php");
    }
    $productsClass = new Products();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>SHOP</title>
    <link rel="stylesheet" href="/static/styles.css">
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
                        <input type="search" name="q" id="search-item" class="form-control form-control-dark"
                            placeholder="Search...">
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
            <h2>
            <span class='text-wrap'>{$title}</span>
            <br/>
                <img src={$photo}>
            </h2>
            <div>
                <span class='description'>{$description}</span>
            </div> 
            ";
            if (!array_key_exists($productId, $_SESSION['cart'])) {
                $html .= "
                <h4>
                    {$price} ₽
                    <a class='cart-add btn btn-outline-dark me-2' product-id={$productId}>To cart</a>
                </h4>
                ";
            } else {
                $html .= "
                <h4>
                    {$price} ₽
                    <a class='cart-add btn btn-outline-dark me-2' href='/cart/show.php' product-id={$productId}>Added to cart</a>
                </h4>
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