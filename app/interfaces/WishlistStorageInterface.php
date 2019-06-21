<?php

namespace app\interfaces;

interface WishlistStorageInterface
{
    public function getAllInWishlist(): array;

    public function addToWishlist(int $productId): bool;

    public function removeFromWishlist(int $productId): bool;

    public function checkInWishlist(int $productId): bool;
}