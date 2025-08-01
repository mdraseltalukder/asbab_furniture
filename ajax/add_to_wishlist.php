<?php
session_start();
include_once('../connection/conn.php');


$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if ($product_id <= 0) {
    echo "Invalid product.";
    exit();
}

if (!isset($_SESSION['user'])) {
  $_SESSION['wishlist_redirect_product'] = $product_id;
    echo 'login_required';
    exit();
}

$user_id = $_SESSION['user']['id'];




// Check if product exists
$check_product = $conn->query("SELECT id FROM product WHERE id = $product_id");
if ($check_product->num_rows == 0) {
    echo "Product not found.";
    exit();
}

// Check if already in wishlist
$check = $conn->query("SELECT * FROM wishlist WHERE user_id = $user_id AND pro_id = $product_id");

if ($check->num_rows > 0) {
    // ✅ Remove if already exists
    $conn->query("DELETE FROM wishlist WHERE user_id = $user_id AND pro_id = $product_id");
    echo "Removed from wishlist.";
} else {
    // ✅ Insert if not exists
    $insert = $conn->query("INSERT INTO wishlist (user_id, pro_id) VALUES ($user_id, $product_id)");
    if ($insert) {
        echo "Added to wishlist!";
    } else {
        echo "Error adding to wishlist.";
    }
}

exit();
?>
