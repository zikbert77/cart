<?php

namespace app\interfaces;

interface ProductStorageInterface
{
    public function getAllProducts(): array;

    public function getProductById(int $productId): array;
}