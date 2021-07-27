<?php
session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    $_SESSION['money'] = 100000;
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
    <link rel="stylesheet" href="/static/styles.css">
    <script defer src="/static/js/cart.js"></script>

    <title>SHOP</title>
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
                    <a class="btn btn-outline-light me-2" href="/admin/login.php">Login</a>
                    <a class="btn btn-outline-light me-2" href="/cart/show.php">Cart</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <?php
        $res = 0;
        $itemsCount = 0;

        foreach ($_SESSION['cart'] as $productKey => $productValue) {
            $res += $productValue['price'] * $productValue['count'];
            $itemsCount += $productValue['count'];
        }
        echo "
            <div id='summary' >Summary {$res} ₽</div>  
            <div id='items-count'>Overall items  {$itemsCount}</div>  
            <div>You have {$_SESSION['money']} ₽ to spend</div>
            ";

        if ($itemsCount > 0) {
            echo "<a class='btn btn-success' id='buy'>Buy</a>";
        }
        ?>
        <div class="d-flex flex-column bd-highlight mb-3">
            <?php
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $productKey => $productValue) {
                    $title = $productValue['title'];
                    $price = $productValue['price'];
                    $photo = $productValue['photo'];
                    $amount = $productValue['count'];
                    $quantity = $productValue['quantity'];
                    echo "
                        <div class='p-2' product-id={$productKey}>
                            <img src='{$photo}'></img>

                            <a class='delete btn btn-outline-dark link-danger' product-id={$productKey}>Delete</a>
                            
                            <a class='link-dark' href='/product/show.php?id={$productKey}'>{$title}</a>

                            <button class='cart-btn decrease btn btn-outline-dark mx-2'>-</button>
                            <span class='amount'>Amount {$amount} </span>
                            <button class='cart-btn increase btn btn-outline-dark mx-2'>+</button>
                            
                            <span class='price'>| Price {$price} ₽|</span>
                            <span class='stock text-info'>| Available in stock {$quantity} |</span>
                        </div>
                        ";
                }
            } else {
                echo 'No items in cart';
            }
            ?>
        </div>
    </div>

</body>

</html>