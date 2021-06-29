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
    <link rel="stylesheet" href="/static/styles/showCart.css">
    <script defer src="/static/js/cart.js"></script>
    <title>SHOP</title>
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

    <div class="container-flex">
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
        if (isset($_SESSION['msg'])) {
            foreach ($_SESSION['msg'] as $msg) {
                echo "<div>{$msg}</div>";
            }
            unset($_SESSION['msg']);
        }
        if ($itemsCount > 0) {
            echo "<a id='buy' style='margin-left:20vw;max-width:max-content;'>Buy</a>";
        }
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $productKey => $productValue) {
                $title = $productValue['title'];
                $price = $productValue['price'];
                $photo = $productValue['photo'];
                $amount = $productValue['count'];
                $quantity = $productValue['quantity'];
                echo "
                <div product-id={$productKey}>
                    <img src='{$photo}'></img>
                    <a class='delete' product-id={$productKey}>Delete</a>
                    <a href='/product/show.php?id={$productKey}'>{$title}</a>

                    <button class='cart-btn decrease'>-</button>
                    <span class='amount' >Amount {$amount}</span>
                    <button class='cart-btn increase'>+</button>
                    
                    <span class='price' >| Price {$price} ₽|</span>
                    <span class='stock' >|Available in stock {$quantity}|</span>
                    <a class='restock' product-id={$productKey}>Restock</a>
                </div>
                ";
            }
        } else {
            echo 'No items in cart';
        }
        ?>

    </div>
</body>

</html>