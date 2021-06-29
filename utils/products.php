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
        $sql = "SELECT id, title, price, photo, quantity from goods where quantity > 0 limit ? offset ?";

        $stmt = $this->getConnection()->prepare($sql);

        $stmt->execute([$limit, $offset]);

        $products = $stmt->fetchAll();

        if (count($products) > 0) return json_encode($products);

        return json_encode(-1);
    }

    public function getProductsByName(string $productName, int $offset = 0)
    {
        $sql  = "select id,title,price,photo,quantity from goods where title ilike ? and quantity > 0 limit 10 offset ?";

        $stmt = $this->getConnection()->prepare($sql);

        $stmt->execute(["%{$productName}%", $offset]);

        $products = $stmt->fetchAll();

        if (count($products) > 0) return json_encode($products);

        return json_encode(-1);
    }

    public function getProductById(int $productId)
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
        $sql  = "update goods set quantity=5 where id = ?";

        $stmt = $this->getConnection()->prepare($sql);

        $stmt->execute([$productId]);

        $products = $stmt->fetch();

        if ($products > 0) return json_encode($products);

        return json_encode(-1);
    }
}