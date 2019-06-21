<?php

namespace app\model;

use app\Storage;

class Wishlist
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
}