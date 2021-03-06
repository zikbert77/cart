<?php

namespace app\storage;

use PDO;
use app\AbstractStorage;

class Sqlite extends AbstractStorage
{
    protected function setConnection(): void
    {
        $pdo = new PDO('sqlite:' . DATABASE_PATH);
        $this->connection = $pdo;
    }

    public function getAllProducts(): array
    {
        $products = [];

        $i = 0;
        foreach ($this->connection->query("SELECT * FROM products") as $row)
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
        $product = [];
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE id = ?");
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

    //Cart
    public function getAllInCart(): array
    {
        $productsInCart = [];

        $query = $this->connection->prepare("
            SELECT 
                c.id,
                c.product_id,
                c.quantity,
                p.title,
                p.price,
                p.image 
            FROM cart c 
            INNER JOIN products p on c.product_id = p.id
        ");

        $query->execute();

        $i = 0;
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $productsInCart[$i]['id'] = $row['id'];
            $productsInCart[$i]['product_id'] = $row['product_id'];
            $productsInCart[$i]['title'] = $row['title'];
            $productsInCart[$i]['price'] = $row['price'];
            $productsInCart[$i]['quantity'] = $row['quantity'];
            $productsInCart[$i]['image'] = $row['image'];
            ++$i;
        }

        return $productsInCart;
    }

    public function getTotalInCartAmount(): float
    {
        $total = 0;

        $inCart = $this->getAllInCart();
        foreach ($inCart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }

    public function addToCart(int $productId, int $quantity): bool
    {
        if ($this->checkInCart($productId)) {
            $stmt = $this->connection->prepare("UPDATE cart SET quantity = quantity + :quantity WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':quantity', $quantity);
        } else {
            $stmt = $this->connection->prepare("INSERT INTO cart (product_id, quantity) VALUES (:product_id, :quantity)");
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':quantity', $quantity);
        }

        return $stmt->execute();
    }

    public function changeQuantity(int $productId, int $quantity): bool
    {
        if ($this->checkInCart($productId)) {
            $stmt = $this->connection->prepare("UPDATE cart SET quantity = :quantity WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':quantity', $quantity);

            return $stmt->execute();
        }

        return false;
    }

    public function removeFromCart(int $productId): bool
    {
        if ($this->checkInCart($productId)) {
            $stmt = $this->connection->prepare("DELETE FROM cart WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $productId);

            return $stmt->execute();
        }

        return false;
    }

    public function checkInCart(int $productId): bool
    {
        $stmt = $this->connection->prepare('SELECT COUNT(*) FROM cart WHERE product_id = ?');
        $stmt->execute([
            $productId
        ]);

        return $stmt->fetchColumn() == 1;
    }

    //WishList
    public function getAllInWishlist(): array
    {
        $productsInWishList = [];

        $query = $this->connection->prepare("
            SELECT 
                w.id,
                w.product_id,
                p.title,
                p.price,
                p.image 
            FROM wishlist w
            INNER JOIN products p on w.product_id = p.id
        ");

        $query->execute();

        $i = 0;
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $productsInWishList[$i]['id'] = $row['id'];
            $productsInWishList[$i]['product_id'] = $row['product_id'];
            $productsInWishList[$i]['title'] = $row['title'];
            $productsInWishList[$i]['price'] = $row['price'];
            $productsInWishList[$i]['image'] = $row['image'];
            ++$i;
        }

        return $productsInWishList;
    }

    public function addToWishlist(int $productId): bool
    {
        if (!$this->checkInWishlist($productId)) {
            $stmt = $this->connection->prepare("INSERT INTO wishlist (product_id) VALUES (:product_id)");
            $stmt->bindParam(':product_id', $productId);

            return $stmt->execute();
        }

        return false;
    }

    public function removeFromWishlist(int $productId): bool
    {
        if ($this->checkInWishlist($productId)) {
            $stmt = $this->connection->prepare("DELETE FROM wishlist WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $productId);

            return $stmt->execute();
        }

        return false;
    }

    public function checkInWishlist(int $productId): bool
    {
        $stmt = $this->connection->prepare('SELECT COUNT(*) FROM wishlist WHERE product_id = ?');
        $stmt->execute([
            $productId
        ]);

        return $stmt->fetchColumn() == 1;
    }
}