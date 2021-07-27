<?php

require "database.php";

class Products extends Database
{
    function __construct()
    {
        parent::__construct();
    }

    public function getProducts(int $limit = 10, int $offset = 0)
    {
        $sql = "SELECT id, title, price, photo, quantity from goods limit ? offset ?";

        $stmt = $this->getConnection()->prepare($sql);

        $stmt->execute([$limit, $offset]);

        $products = $stmt->fetchAll();

        if (count($products) > 0) return json_encode($products);

        return json_encode(-1);
    }

    public function getProductsByName(string $productName, int $offset = 0)
    {
        $sql  = "select id,title,price,photo,quantity from goods where title ilike ? limit 10 offset ?";

        $stmt = $this->getConnection()->prepare($sql);

        $stmt->execute(["%{$productName}%", $offset]);

        $products = $stmt->fetchAll();

        if (count($products) > 0) return json_encode($products);

        return json_encode(-1);
    }

    public function getProductById(int|string $productId)
    {
        $sql  = "select id, title, description, price, photo, quantity from goods where id = ? limit 1";

        $stmt = $this->getConnection()->prepare($sql);

        $stmt->execute([$productId]);

        $products = $stmt->fetch();

        if ($products > 0) return json_encode($products);

        return json_encode(-1);
    }
    public function getProductsById(array $productIds)
    {
        $qstn = str_repeat("?,", count($productIds) - 1) . "?";

        $sql = "select id, quantity from goods where id in ({$qstn})";

        $stmt = $this->getConnection()->prepare($sql);

        $stmt->execute($productIds);

        $productsInfo = $stmt->fetchAll();

        return json_encode($productsInfo);
    }
    public function removeFromStock(array $productsAssoc)
    {
        $conn = $this->getConnection();
        try {
            $conn->beginTransaction();
            foreach ($productsAssoc as $productId => $productInfo) {
                $sql = "UPDATE goods set quantity=quantity-:qtyRemove where id=:prodId";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':qtyRemove' => $productInfo['count'], ':prodId' => $productId]);
            }

            $conn->commit();

            return json_encode(1);
        } catch (Exception $e) {
            $conn->rollBack();

            return json_encode(-1);
        }
    }
    public function restock(int $productId)
    {
        $sql  = "update goods set quantity=quantity+5 where id = ?";

        $stmt = $this->getConnection()->prepare($sql);

        $stmt->execute([$productId]);

        $products = $stmt->fetch();

        if ($products > 0) return json_encode($products);

        return json_encode(-1);
    }
    public function createProduct($title, $description, $price, $photo)
    {
        $conn = $this->getConnection();

        $sql = 'INSERT INTO goods (title,description,price,photo) values (:title,:description,:price,:photo) returning title,description,price,photo';

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':price' => $price,
            ':photo' => $photo,
        ]);

        $product = $stmt->fetch();

        if ($product > 0) return json_encode($product);

        return json_encode(-1);
    }
    public function deleteProduct($productId)
    {
        $conn = $this->getConnection();

        $sql = 'DELETE FROM goods where id = ? returning id';

        $stmt = $conn->prepare($sql);

        $stmt->execute([$productId]);

        $product = $stmt->fetch();

        if ($product > 0) return json_encode($product);

        return json_encode(-1);
    }
    public function updateProduct($id, $title = null, $description = null, $price = null, $photo = null)
    {
        $fields = array_filter(array_slice(get_defined_vars(), 1), function ($entry) {
            return $entry != null;
        });

        $conn = $this->getConnection();


        $keyValue = implode(",", array_map(
            function ($value, $key) {
                return "{$key}='{$value}'";
            },
            $fields,
            array_keys($fields)
        ));

        $sql = "UPDATE goods set {$keyValue} where id=? returning id";

        $stmt = $conn->prepare($sql);

        $stmt->execute([$id]);

        $product = $stmt->fetch();

        if ($product > 0) return json_encode($product);

        return json_encode(-1);
    }
}