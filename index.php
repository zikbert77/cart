<?php

use app\Storage;
use app\model\Cart;
use app\model\Product;
use app\model\Wishlist;

/**
 * Show exceptions in dev environment
 */
ini_set('display_errors',1);
error_reporting(E_ALL);

/**
 * Set project path in constant ROOT
 */
define('ROOT', str_replace('\\','/', dirname(__FILE__)));

/**
 * Config file
 */
require_once(ROOT . '/config.php');
require_once(ROOT . '/helpers.php');

/**
 * PSR-4 classes autoload
 */
require_once(ROOT . '/vendor/autoload.php');

$storage = new Storage();

$productModel = new Product($storage);
$products = $productModel->getAllProducts();

$cartModel = new Cart($storage);
$inCart = $cartModel->getAllInCart();

$wishlistModel = new Wishlist($storage);
$inWishlist = $wishlistModel->getAllInWishlist();

// Cart
if (isset($_POST['addToCart'])) {
    return $cartModel->addToCart($_POST['productId'], $_POST['quantity']);
}

if (isset($_POST['changeQuantity'])) {
    return $cartModel->changeQuantity($_POST['productId'], $_POST['quantity']);
}

if (isset($_POST['removeFromCart'])) {
    return $cartModel->removeFromCart($_POST['productId']);
}

if (isset($_POST['getTotalAmount'])) {
    die($cartModel->getTotalInCartAmount());
}

// Wishlist
if (isset($_POST['addToWishlist'])) {
    return $wishlistModel->addToWishlist($_POST['productId']);
}

if (isset($_POST['removeFromWishlist'])) {
    return $wishlistModel->removeFromWishlist($_POST['productId']);
}

// Simple "routing"
$action = $_GET['action'] ?? 'products';
$view = ROOT . "/view/{$action}.php";

if (file_exists($view)) {
    return require_once($view);
}

