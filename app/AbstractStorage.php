<?php

namespace app;

use app\interfaces\CartStorageInterface;
use app\interfaces\ProductStorageInterface;
use app\interfaces\WishlistStorageInterface;
use Predis\Client;

abstract class AbstractStorage implements ProductStorageInterface, CartStorageInterface, WishlistStorageInterface
{
    /** @var \PDO|Client */
    protected $connection;

    abstract protected function setConnection();

    public function __construct()
    {
        $this->setConnection();
    }
}