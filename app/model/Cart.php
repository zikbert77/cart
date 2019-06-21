<?php

namespace app\model;

use app\Storage;
use app\interfaces\CartStorageInterface;

class Cart implements CartStorageInterface
{
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage->getStorage();
    }

    public function getAllInCart(): array
    {
        return $this->storage->getAllInCart();
    }

    public function addToCart(int $id, int $quantity): bool
    {
        return $this->storage->addToCart($id, $quantity);
    }

    public function changeQuantity(int $id, int $quantity): bool
    {
        return $this->storage->changeQuantity($id, $quantity);
    }

    public function removeFromCart(int $id): bool
    {
        return $this->storage->removeFromCart($id);
    }

    public function getTotalInCartAmount(): float
    {
        return $this->storage->getTotalInCartAmount();
    }

    public function checkInCart(int $productId): bool
    {
        return $this->storage->checkInCart($productId);
    }
}