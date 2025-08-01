<?php 
include_once('../../connection/conn.php');

// delete category
if( isset($_GET['id']) && !isset($_GET['created_at']) && !isset($_GET['created_date']) && !isset($_GET['name']) && !isset($_GET['coupon']) && !isset($_GET['username'])  ){
    $id = $_GET['id'];
    $delete = "DELETE FROM categories WHERE id = $id";
    $conn->query($delete);
    header("Location: category.php");
    exit();
}
// delete contact us
if( isset($_GET['id']) && isset($_GET['created_at']) && !isset($_GET['created_date']) && !isset($_GET['name']) && !isset($_GET['coupon']) && !isset($_GET['username'])   ){
    $id = $_GET['id'];
    $delete = "DELETE FROM contact_us WHERE id = $id";
    $conn->query($delete);
    header("Location: contact-us.php");
    exit();
}
// delete user
if( isset($_GET['id']) && isset($_GET['created_date']) && !isset($_GET['created_at']) && !isset($_GET['name']) && !isset($_GET['coupon']) && !isset($_GET['username'])   ){
    $id = $_GET['id'];
    $delete = "DELETE FROM users WHERE id = $id";
    $conn->query($delete);
    header("Location: user.php");
    exit();
}

// delete product
if( isset($_GET['id']) && !isset($_GET['created_at']) && !isset($_GET['created_date']) && isset($_GET['name']) && !isset($_GET['coupon']) && !isset($_GET['username']) ){
    $id = $_GET['id'];


$selectImage="SELECT image FROM product WHERE id=$id";
$result=$conn->query($selectImage);
if($result->num_rows > 0){

    $product=$result->fetch_assoc();
    $image=$product['image'];
    $img_path="../images/product/" .$image;
   
   if(!empty($image) && file_exists($img_path)){
    unlink($img_path);
   }


    $delete = "DELETE FROM product WHERE id = $id";
    $conn->query($delete);

    
}
    header("Location: product.php");
    exit();
}
// delete coupon
if( isset($_GET['id']) && !isset($_GET['created_at']) && !isset($_GET['created_date']) && !isset($_GET['name']) && isset($_GET['coupon']) && !isset($_GET['username'])){
    $id = $_GET['id'];

    $delete = "DELETE FROM coupon WHERE id = $id";
    $conn->query($delete);

    header("Location: coupon.php");
    exit();
}
// delete vendor
if( isset($_GET['id']) && !isset($_GET['created_at']) && !isset($_GET['created_date']) && !isset($_GET['name']) && !isset($_GET['coupon']) && isset($_GET['username']) ){
    $id = $_GET['id'];

    $delete = "DELETE FROM admin_users WHERE id = $id";
    $conn->query($delete);

    header("Location: vendor_management.php");
    exit();
}

?>