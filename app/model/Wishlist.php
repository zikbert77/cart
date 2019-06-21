<?php

namespace app\model;

use app\Storage;
use app\interfaces\WishlistStorageInterface;

class Wishlist implements WishlistStorageInterface
{
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage->getStorage();
    }

    public function getAllInWishlist(): array
    {
        return $this->storage->getAllInWishlist();
    }

    public function addToWishlist(int $id): bool
    {
        return $this->storage->addToWishlist($id);
    }

    public function removeFromWishlist(int $id): bool
    {
        return $this->storage->removeFromWishlist($id);
    }

    public function checkInWishlist(int $productId): bool
    {
        return $this->storage->checkInWishlist($productId);
    }
}