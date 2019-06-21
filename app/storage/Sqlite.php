<?php

namespace app\storage;

use PDO;
use app\AbstractStorage;
use app\StorageInterface;

class Sqlite extends AbstractStorage implements StorageInterface
{
    protected function setConnection(): void
    {
        $pdo = new PDO('sqlite:' . DATABASE_PATH);
        $this->connection = $pdo;
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
            //update
            $stmt = $this->connection->prepare("UPDATE cart SET quantity = quantity + :quantity WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':quantity', $quantity);

            $stmt->execute();
        } else {
            //insert
            $stmt = $this->connection->prepare("INSERT INTO cart (product_id, quantity) VALUES (:product_id, :quantity)");
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':quantity', $quantity);

            $stmt->execute();
        }

        return false;
    }

    public function changeQuantity(int $productId, int $quantity): bool
    {
        if ($this->checkInCart($productId)) {
            //update
            $stmt = $this->connection->prepare("UPDATE cart SET quantity = :quantity WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $productId);
            $stmt->bindParam(':quantity', $quantity);

            $stmt->execute();
        }

        return false;
    }

    public function removeFromCart(int $productId): bool
    {
        if ($this->checkInCart($productId)) {
            //update
            $stmt = $this->connection->prepare("DELETE FROM cart WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $productId);

            $stmt->execute();
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

    //Wishlist
    public function getAllInWishlist(): array
    {
        $productsInWishlist = [];

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
            $productsInWishlist[$i]['id'] = $row['id'];
            $productsInWishlist[$i]['product_id'] = $row['product_id'];
            $productsInWishlist[$i]['title'] = $row['title'];
            $productsInWishlist[$i]['price'] = $row['price'];
            $productsInWishlist[$i]['image'] = $row['image'];
            ++$i;
        }

        return $productsInWishlist;
    }

    public function addToWishlist(int $productId): bool
    {
        if (!$this->checkInWishlist($productId)) {
            $stmt = $this->connection->prepare("INSERT INTO wishlist (product_id) VALUES (:product_id)");
            $stmt->bindParam(':product_id', $productId);

            $stmt->execute();

            return true;
        }

        return false;
    }
    public function removeFromWishlist(int $productId): bool
    {
        if ($this->checkInWishlist($productId)) {
            //update
            $stmt = $this->connection->prepare("DELETE FROM wishlist WHERE product_id = :product_id");
            $stmt->bindParam(':product_id', $productId);

            $stmt->execute();

            return true;
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