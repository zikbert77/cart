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

    public function getAllProducts(): array
    {
        $products = $this->connection->get('products');
        if (!empty($products)) {
            $parsedProducts = json_decode($products, true);
            if ($parsedProducts) {
                return $parsedProducts;
            }
        }
        
        return [];
    }

    public function getProductById(int $productId): array
    {
        $product = [];
        $products = $this->connection->get('products');
        if (!empty($products)) {
            $parsedProducts = json_decode($products, true);
            if ($parsedProducts) {
                $productKey = array_search($productId, array_column($parsedProducts, 'id'));
                if ($productKey !== false) {
                    return $parsedProducts[$productKey];
                }
            }
        }

        return $product;
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
            $inCart[$this->getElementIndex($inCart, $productId)]['quantity'] += $quantity;
        } else {
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
        if ($this->checkInCart($productId)) {
            $inCart[$this->getElementIndex($inCart, $productId)]['quantity'] = $quantity;
        }

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
        $inWishList = $this->connection->get('wishlist');
        $productsInWishList = [];

        $i = 0;
        foreach (json_decode($inWishList, true) ?? [] as $item) {
            $product = $this->getProductById($item['product_id']);

            $productsInWishList[$i]['id'] = $i;
            $productsInWishList[$i]['product_id'] = $product['id'];
            $productsInWishList[$i]['title'] = $product['title'];
            $productsInWishList[$i]['price'] = $product['price'];
            $productsInWishList[$i]['image'] = $product['image'];

            $i++;
        }

        return $productsInWishList;
    }

    public function addToWishlist(int $productId): bool
    {
        $inWishList = $this->getAllInWishlist();

        if ($this->checkInWishlist($productId)) {
            //do nothing
        } else {
            //insert
            array_push($inWishList, [
                'product_id' => $productId,
            ]);
        }

        $this->connection->set('wishlist', json_encode($inWishList));

        return true;
    }

    public function removeFromWishlist(int $productId): bool
    {
        $inWishList = $this->getAllInWishlist();
        if ($this->checkInWishlist($productId)) {
            unset($inWishList[$this->getElementIndex($inWishList, $productId)]);
        }

        $this->connection->set('wishlist', json_encode($inWishList));

        return true;
    }

    public function checkInWishlist(int $productId): bool
    {
        $inWishList = $this->getAllInWishlist();
        foreach ($inWishList as $item) {
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