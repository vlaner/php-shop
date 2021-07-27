<?php
session_start();
require_once "../utils/products.php";
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    die();
}
$productsClass = new Products();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script defer src="/static/js/admin/createProd.js"></script>
    <title>Add product</title>
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

            </div>
        </div>
    </header>
    <div class="container">

        <div class="form-group">
            <label for="Title">Title</label>
            <input type="text" class="form-control" id="title" placeholder="Prodcut title (255 chars)" required>
        </div>

        <div class="form-group">
            <label for="Description">Description</label>
            <textarea class="form-control" id="description" placeholder="Enter product description" required></textarea>
        </div>

        <div class="form-group">
            <label for="Price">Price</label>
            <input type="number" class="form-control" id="price" required>
        </div>

        <div class="form-group">
            <label for="Photo">Product photo</label>
            <input type="file" class="form-control" id="photo" accept="image/png, image/jpeg, image/jpg" />
        </div>

        <button id='prod-submit' type="submit" class="mt-3 btn btn-primary">Add product</button>
    </div>
</body>

</html>