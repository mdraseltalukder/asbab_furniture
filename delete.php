<?php 
include_once('includes/header.php');

if(isset($_GET['id']) && !isset($_GET['user_id']) && !isset($_GET['categories_id']) ){
$id=$_GET['id'];
$delete="DELETE FROM add_to_cart WHERE id = $id";
$conn->query($delete);
header("Location: cart.php");
exit();

}

if(isset($_GET['id']) && isset($_GET['user_id']) && !isset($_GET['categories_id'])  ){
$id=$_GET['id'];
$delete="DELETE FROM add_to_cart WHERE id = $id";
$conn->query($delete);
header("Location: checkout.php");
exit();

}
if(isset($_GET['id']) && !isset($_GET['user_id']) && isset($_GET['categories_id']) ){
$id=$_GET['id'];
$delete="DELETE FROM wishlist WHERE pro_id = $id";
$conn->query($delete);
header("Location: wishlist.php");
exit();

}

?>