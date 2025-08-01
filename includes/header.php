<?php 
session_start();
ob_start();
define('APPURL', 'http://localhost/dashboard/Projects/asbab_furniture');
include_once(__DIR__ . '/../connection/conn.php');

$select="SELECT * FROM categories";
$result=$conn->query($select);
$categories=$result->fetch_all(MYSQLI_ASSOC);


// addd to cart

if(isset($_SESSION['user']['id'])){
$user_id = $_SESSION['user']['id'];

$select="SELECT * FROM add_to_cart WHERE user_id = $user_id";
$num_row=$conn->query($select);
}

// wishlist
$wishlist_count = 0;
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user']['id'];
    $sql = "SELECT COUNT(*) as total FROM wishlist WHERE user_id = $user_id";
    $res = $conn->query($sql);
    $wishlist_count = $res->fetch_assoc()['total'];
}
?>

<?php 
// meta tags

$id= $_GET['id'] ?? 0;
$select_product = "SELECT * FROM product WHERE id = $id";
$result_product = $conn->query($select_product);
$product = $result_product->fetch_assoc();


$meta_title = $product['meta_title'] ?? 'Asbab Furniture'; 
$meta_desc = $product['meta_desc'] ?? 'Best furniture store';
$meta_keywords = $product['meta_keyword'] ?? 'furniture, home decor, interior design';


?>

<!doctype php>
<php class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= $meta_title ?>  </title>
    <meta name="description" content="<?= $meta_desc ?>">
    <meta name="keyword" content="<?= $meta_keywords ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon" type="image/x-icon" href="<?= APPURL ?>/images/favicon.ico">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    

    <!-- All css files are included here. -->
    <!-- Bootstrap fremwork main css -->
    <link rel="stylesheet" href="<?= APPURL ?>/css/bootstrap.min.css">
    <!-- Owl Carousel min css -->
    <link rel="stylesheet" href="<?= APPURL ?>/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?= APPURL ?>/css/owl.theme.default.min.css">
    <!-- This core.css file contents all plugings css file. -->
    <link rel="stylesheet" href="<?= APPURL ?>/css/core.css">
    <!-- Theme shortcodes/elements style -->
    <link rel="stylesheet" href="<?= APPURL ?>/css/shortcode/shortcodes.css">
    <!-- Theme main style -->
    <link rel="stylesheet" href="<?= APPURL ?>/style.css">
    <!-- Responsive css -->
    <link rel="stylesheet" href="<?= APPURL ?>/css/responsive.css">
    <!-- User style -->
    <link rel="stylesheet" href="<?= APPURL ?>/css/custom.css">


    <!-- Modernizr JS -->
    <script src="js/vendor/modernizr-3.5.0.min.js"></script>
</head>

<body>
  

    <!-- Body main wrapper start -->
    <div class="wrapper">
        <!-- Start Header Style -->
        <header id="htc__header" class="htc__header__area header--one">
            <!-- Start Mainmenu Area -->
            <div id="sticky-header-with-topbar" class="sticky__header mainmenu__wrap">
                <div class="container">
                    <div class="row">
                        <div class="clearfix menumenu__container">
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-5"> 
                                <div class="logo">
                                     <a href="<?= APPURL ?>"><img src="images/logo/4.png" alt="logo images"></a>
                                </div>
                            </div>
                            <div class="col-md-7 col-lg-8 col-sm-5 col-xs-3">
                                <nav class="hidden-sm hidden-xs main__menu__nav">
                                    <ul class="main__menu">
                                        <li class="drop"><a href="<?= APPURL ?>/index.php">Home</a></li>
                                        

                                        <?php foreach($categories as $category){ ?>
                                        
                                        <li class="drop"><a href="<?= APPURL ?>/categories.php?id=<?= $category['id'] ?>"><?= $category['categories'] ?></a>
                                           
                                        </li>
                                        <?php }?>
                                        <li><a href="contact.php">contact</a></li>
                                    </ul>
                                </nav>
                                

                                <div class="visible-sm visible-xs clearfix mobile-menu">
                                    <nav id="mobile_dropdown">
                                        <ul>
                                             <li class="drop"><a href="<?= APPURL ?>/index.php">Home</a></li>
                                        

                                        <?php foreach($categories as $category){ ?>
                                        
                                        <li class="drop"><a href="<?= APPURL ?>/categories.php?id=<?= $category['id'] ?>"><?= $category['categories'] ?></a>
                                           
                                        </li>
                                        <?php }?>
                                        <li><a href="contact.php">contact</a></li>
                                        </ul>
                                    </nav>
                                </div>  
                            </div>
                            <div class="col-md-3 col-lg-2 col-sm-4 col-xs-4">
                                <div class="header__right">
                                     <div class="header__search search search__open">
                                        <a href="<?= APPURL ?>/search.php"><i class="icon-magnifier icons"></i></a>
                                    </div>
                                    <?php if(isset($_SESSION['user'])){?>
                                  
                                      <div class="header__account">
                                       

                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <?= $_SESSION['user']['name'] ?>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="padding: 15px 0 15px 10px; ">
                                                <a class="dropdown-item" href="<?= APPURL ?>/my-profile.php">My Profile</a>
                                             <hr>
                                                <a class="dropdown-item" href="<?= APPURL ?>/my-order.php">My Order</a>
                                  <hr>
                                                <a class="dropdown-item" href="<?= APPURL ?>/wishlist.php">My Wishlist</a>
                                  <hr>
                                                <a class="dropdown-item" href="<?= APPURL ?>/logout.php">Logout</a>
                                            </div>
                                            </li>
                                    </div>
                              
                                    <div class="htc__shopping__cart">
                                        <a class="cart__menu" href="<?= APPURL ?>/cart.php"><i class="icon-handbag icons"></i></a>
                                        <a href="<?= APPURL ?>/cart.php"><span class="htc__qua"><?= $num_row->num_rows ?? 0; ?></span></a>
                                    </div>
                                    <div class="htc__shopping__cart" style="margin-left: 20px;">
                                        <a class="cart__menu" href="<?= APPURL ?>/wishlist.php"><i class="icon-heart icons"></i></a>
                                        <a href="<?= APPURL ?>/wishlist.php"><span class="htc__qua wishlist-count"><?= $wishlist_count ?></span></a>
                                    </div>
                                    <?php }else{?>
   <div class="header__account">
                                        <a href="<?= APPURL ?>/login.php">Login/Register</a>
                                    </div>

                                        <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mobile-menu-area"></div>
                </div>
            </div>
            <!-- End Mainmenu Area -->
        </header>
           <div class="body__overlay"></div>
        <!-- Start Offset Wrapper -->
        <div class="offset__wrapper">
            <!-- Start Search Popap -->
            <div class="search__area">
                <div class="container" >
                    <div class="row" >
                        <div class="col-md-12" >
                            <div class="search__inner">
                                <form action="search.php" method="get">
                                    <input placeholder="Search here... " type="text" name="str">
                                    <button type="submit"></button>
                                </form>
                                <div class="search__close__btn">
                                    <span class="search__close__btn_icon"><i class="zmdi zmdi-close"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
   
      

