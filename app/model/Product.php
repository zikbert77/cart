<?php

namespace app\model;

use app\Storage;

class Product
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

}