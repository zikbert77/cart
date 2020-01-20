<?php

namespace app\model;

use app\Storage;
use app\interfaces\ProductStorageInterface;

class Product implements ProductStorageInterface
{
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage->getStorage();
    }

    public function getAllProducts(): array
    {
        return $this->storage->getAllProducts();
    }

    public function getProductById(int $productId): array
    {
        return $this->storage->getProductById($productId);
    }
}