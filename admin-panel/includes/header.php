<?php
// ⚠️ PHP ট্যাগ বন্ধ করো না
session_start();
define('ADMINURL', 'http://localhost/dashboard/Projects/asbab_furniture/admin-panel');
ob_start(); 
?>

<!doctype html>
<html class="no-js" lang="">
   <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Dashboard Page</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="<?= ADMINURL ?>/assets/css/normalize.css">
      <link rel="stylesheet" href="<?= ADMINURL ?>/assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="<?= ADMINURL ?>/assets/css/font-awesome.min.css">
      <link rel="stylesheet" href="<?= ADMINURL ?>/assets/css/themify-icons.css">
      <link rel="stylesheet" href="<?= ADMINURL ?>/assets/css/pe-icon-7-filled.css">
      <link rel="stylesheet" href="<?= ADMINURL ?>/assets/css/flag-icon.min.css">
      <link rel="stylesheet" href="<?= ADMINURL ?>/assets/css/cs-skin-elastic.css">
      <link rel="stylesheet" href="<?= ADMINURL ?>/assets/css/style.css">
      
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
   </head>
   <body>
      <aside id="left-panel" class="left-panel">
         <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="collapse navbar-collapse main-menu">
               <ul class="nav navbar-nav">
                  <li class="menu-title">Menu</li>
                               <?php 
                  if($_SESSION['admin']['role'] == 0){

                  ?>
                 
                  <li class="menu-item-has-children dropdown">
                     <a href="<?= ADMINURL ?>/admin/category.php" > Category Master</a>
                  </li>
                         <?php }?>
      
                  
                  <li class="menu-item-has-children dropdown">
                     <a href="<?= ADMINURL ?>/admin/product.php" >Product Master</a>
                  </li>
				  <li class="menu-item-has-children dropdown">
                             <?php 
                  if($_SESSION['admin']['role'] == 0){

                  ?>
                     <a href="<?= ADMINURL ?>/admin/order.php" > Order Master</a>
                     <?php }else{?>
 <a href="<?= ADMINURL ?>/admin/order_vendor.php" > Order Master</a>
                        <?php }?>
                        <li class="menu-item-has-children dropdown">
                     <a href="<?= ADMINURL ?>/admin/vendor_management.php" > Vendor Management</a>
                  </li>
                  </li>
<li class="menu-item-has-children dropdown">
                     <a href="<?= ADMINURL ?>/admin/user.php" > User Master</a>
                  </li>
                  </li>

           
           <?php 
                  if($_SESSION['admin']['role'] == 0){

                  ?>
				  <li class="menu-item-has-children dropdown">
                     <a href="<?= ADMINURL ?>/admin/coupon.php" > Coupon Master</a>
                  </li>
				  
				  <li class="menu-item-has-children dropdown">
                     <a href="<?= ADMINURL ?>/admin/contact-us.php" >Contact Us</a>
                  </li>
                     <?php }?>
               </ul>
            </div>
         </nav>
      </aside>
      <section id="right-panel" class="right-panel">
         <header id="header" class="header">
            <div class="top-left">
               <div class="navbar-header">
                  <a class="navbar-brand" href="<?= ADMINURL ?>"><img src="<?= ADMINURL ?>/images/logo.png" alt="Logo"></a>
                  <a class="hidden navbar-brand" href="<?= ADMINURL ?>"><img src="<?= ADMINURL ?>/images/logo2.png" alt="Logo"></a>
                  <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
               </div>
            </div>
            <div class="top-right">
               <div class="header-menu">
                  <div class="float-right user-area dropdown">
                     <a href="javascript:void(0);" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
   Welcome
   <?php if(isset($_SESSION['admin']['username'])){echo $_SESSION['admin']['username'];}else{echo "Admin";}?>
</a>
                  <?php if(isset($_SESSION['admin']['username'])){?>
                     <div class="user-menu dropdown-menu">
                        <a class="nav-link" href="<?= ADMINURL ?>/logout.php"><i class="fa fa-power-off"></i>Logout</a>
                     </div>
                     <?php }?>
                  </div>
               </div>
            </div>
         </header>