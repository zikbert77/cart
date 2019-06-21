<?php

namespace app;

interface StorageInterface
{
    // For ProductModel
    public function getAllProducts(): array;

    // For CartModel
    public function getAllInCart(): array;

    public function getTotalInCartAmount(): float;

    public function addToCart(int $productId, int $quantity): bool;

    public function changeQuantity(int $productId, int $quantity): bool;

    public function removeFromCart(int $productId): bool;

    public function checkInCart(int $productId): bool;

    // For Wishlist
    public function getAllInWishlist(): array;

    public function addToWishlist(int $productId): bool;

    public function removeFromWishlist(int $productId): bool;

    public function checkInWishlist(int $productId): bool;
}