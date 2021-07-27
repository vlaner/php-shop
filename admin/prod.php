<?php
session_start();
require_once "../utils/products.php";
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    die();
}
$productsClass = new Products();

$_SESSION['offset'] = 0;

if (isset($_GET['q'])) {
    $get =  filter_var($_GET['q'], FILTER_SANITIZE_STRING);
    $products = $productsClass->getProductsByName($get, $_SESSION['offset']);
} else {
    $products = $productsClass->getProducts(10, $_SESSION['offset']);
    $_SESSION['offset'] += 8;
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
    <script defer src="/static/js/admin/index.js"></script>
    <title>Index</title>
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

                <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" action="">
                    <div class="input-group">
                        <input type="search" name="q" id="search-item" class="form-control form-control-dark"
                            placeholder="Search...">
                        <button type="submit" class="btn btn-secondary">Search</button>
                    </div>
                </form>

                <div class="text-end">
                    <a class="btn btn-outline-light me-2" href="/admin/logout.php">Logout</a>
                    <a class="btn btn-outline-light me-2" href="/admin/add-product.php">Add product</a>
                </div>
            </div>
        </div>
    </header>
    <div class="container">
        <?php
        if (isset($_SESSION['msg'])) {
            echo "<div class='text-danger'>{$_SESSION['msg']}</div>";
            unset($_SESSION['msg']);
        }
        $products =  json_decode($products, true);
        if (is_array($products)) {
            foreach ((array)$products as $productKey => $productValue) {
                $html = '';
                $title = $productValue['title'];
                $price = $productValue['price'];
                $photo = $productValue['photo'];
                $productId = $productValue['id'];
                $quantity = $productValue['quantity'];

                echo  "
                <div class='card my-3 p-2'>
            
                    <a class='link-dark' href='/product/show.php?id={$productId}'>
                        <span>{$title}</span>
                    </a>
                    <span class='stock' >|Available in stock {$quantity}|</span>
                    
                    <div class='btn-group'>

                        <div>
                            {$price} ₽
                            <a class='btn btn-outline-dark restock' product-id={$productId}>Restock (+5)</a>
                        </div>

                        <div class='ms-2'>
                            <a class='btn btn-danger delete' product-id={$productId}>Delete</a>
                        </div>
                        
                        <div class='ms-2'>
                            <a class='btn btn-info update' href='/admin/update-prod.php?id={$productId}' product-id={$productId}>Update</a>
                        </div>
                        
                    </div>

                </div>
                ";
            }
        } else {
            echo 'No items found';
        }
        ?>
    </div>
    <div class="mt-5 d-flex align-items-center justify-content-center">
        <button type="button" class='text-center btn btn-outline-dark me-2' id="show-more">Show more</button>
    </div>
</body>

</html>