<?php

namespace app;

use PDO;
use app\interfaces\CartStorageInterface;
use app\interfaces\ProductStorageInterface;
use app\interfaces\WishlistStorageInterface;

abstract class AbstractStorage implements ProductStorageInterface, CartStorageInterface, WishlistStorageInterface
{
    /** @var \PDO|\Predis\Client */
    protected $connection;

    abstract protected function setConnection();

    public function __construct()
    {
        $this->setConnection();
    }

    public function getAllProducts(): array
    {
        $connection = $this->connection;

        if ($connection instanceof \PDO) {
            //do nothing
        } else {
            $connection = new \PDO('sqlite:' . DATABASE_PATH);
        }

        $products = [];

        $i = 0;
        foreach ($connection->query("SELECT * FROM products") as $row)
        {
            $products[$i]['id'] = $row['id'];
            $products[$i]['title'] = $row['title'];
            $products[$i]['price'] = $row['price'];
            $products[$i]['image'] = $row['image'];
            ++$i;
        }

        return $products;
    }

    public function getProductById(int $productId): array
    {
        $connection = $this->connection;

        if ($connection instanceof PDO) {
            //do nothing
        } else {
            $connection = new PDO('sqlite:' . DATABASE_PATH);
        }

        $product = [];
        $stmt = $connection->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$productId]);

        $i = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product[$i]['id'] = $row['id'];
            $product[$i]['title'] = $row['title'];
            $product[$i]['price'] = $row['price'];
            $product[$i]['image'] = $row['image'];
            ++$i;
        }

        return $product[0];
    }
}