<?php

namespace app;

use Exception;

class Storage
{
    /** @var AbstractStorage|null $storage */
    private $storage = null;

    public function __construct()
    {
        $storageClass = '\app\storage\\' . ucfirst(strtolower(STORAGE));

        if (class_exists($storageClass)) {
            $this->storage = new $storageClass();
        } else {
            throw new Exception('Storage class not found: ' . STORAGE);
        }
    }

    public function getStorage(): AbstractStorage {
        return $this->storage;
    }
}