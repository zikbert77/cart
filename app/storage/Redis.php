<?php

namespace app\storage;

use Predis\Client;

use app\AbstractStorage;

class Redis extends AbstractStorage
{
    protected function setConnection(): void
    {
        $redis = new Client();
        $this->connection = $redis;
    }

    public function getAllInCart(): array
    {
        $inCart = $this->connection->get('cart');
        $productsInCart = [];

        $i = 0;
        foreach (json_decode($inCart, true) ?? [] as $item) {
            $product = $this->getProductById($item['product_id']);

            $productsInCart[$i]['id'] = $i;
            $productsInCart[$i]['product_id'] = $product['id'];
            $productsInCart[$i]['title'] = $product['title'];
            $productsInCart[$i]['price'] = $product['price'];
            $productsInCart[$i]['quantity'] = $item['quantity'];
            $productsInCart[$i]['image'] = $product['image'];

            $i++;
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
        $inCart = $this->getAllInCart();

        if ($this->checkInCart($productId)) {
            //update
            $inCart[$this->getElementIndex($inCart, $productId)]['quantity'] += $quantity;
        } else {
            //insert
            array_push($inCart, [
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        $this->connection->set('cart', json_encode($inCart));

        return true;
    }

    public function changeQuantity(int $productId, int $quantity): bool
    {
        $inCart = $this->getAllInCart();

        dump($inCart);
        dump($productId);
        dump($this->getElementIndex($inCart, $productId));

        if ($this->checkInCart($productId)) {
            $inCart[$this->getElementIndex($inCart, $productId)]['quantity'] = $quantity;
        }

        dump($inCart);

        $this->connection->set('cart', json_encode($inCart));

        return true;
    }

    public function removeFromCart(int $productId): bool
    {
        $inCart = $this->getAllInCart();

        if ($this->checkInCart($productId)) {
            unset($inCart[$this->getElementIndex($inCart, $productId)]);
        }

        $this->connection->set('cart', json_encode($inCart));

        return true;
    }

    public function checkInCart(int $productId): bool
    {
        $inCart = $this->getAllInCart();

        foreach ($inCart as $item) {
            if ($item['product_id'] == $productId) {
                return true;
            }
        }

        return false;
    }

    public function getAllInWishlist(): array
    {
        $inWishlist = $this->connection->get('wishlist');

        $productsInWishlist = [];

        $i = 0;
        foreach (json_decode($inWishlist, true) ?? [] as $item) {
            $product = $this->getProductById($item['product_id']);

            $productsInWishlist[$i]['id'] = $i;
            $productsInWishlist[$i]['product_id'] = $product['id'];
            $productsInWishlist[$i]['title'] = $product['title'];
            $productsInWishlist[$i]['price'] = $product['price'];
            $productsInWishlist[$i]['image'] = $product['image'];

            $i++;
        }

        return $productsInWishlist;
    }

    public function addToWishlist(int $productId): bool
    {
        $inWishlist = $this->getAllInWishlist();

        if ($this->checkInWishlist($productId)) {
            //do nothing
        } else {
            //insert
            array_push($inWishlist, [
                'product_id' => $productId,
            ]);
        }

        $this->connection->set('wishlist', json_encode($inWishlist));

        return true;
    }

    public function removeFromWishlist(int $productId): bool
    {
        $inWishlist = $this->getAllInWishlist();

        if ($this->checkInWishlist($productId)) {
            unset($inWishlist[$this->getElementIndex($inWishlist, $productId)]);
        }

        $this->connection->set('wishlist', json_encode($inWishlist));

        return true;
    }

    public function checkInWishlist(int $productId): bool
    {
        $inWishlist = $this->getAllInWishlist();

        foreach ($inWishlist as $item) {
            if ($item['product_id'] == $productId) {
                return true;
            }
        }

        return false;
    }

    private function getElementIndex(array $list, int $productId): int
    {
        $i = 0;
        foreach ($list as $item) {
            if ($item['product_id'] == $productId) {
                return $i;
                break;
            }

            $i++;
        }

        return 0;
    }
}