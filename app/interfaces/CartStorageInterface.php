<?php

namespace app\interfaces;

interface CartStorageInterface
{
    public function getAllInCart(): array;

    public function getTotalInCartAmount(): float;

    public function addToCart(int $productId, int $quantity): bool;

    public function changeQuantity(int $productId, int $quantity): bool;

    public function removeFromCart(int $productId): bool;

    public function checkInCart(int $productId): bool;
}