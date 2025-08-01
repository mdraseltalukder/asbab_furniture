   <?php include_once('includes/header.php');
    
    if(!isset($_SESSION['user'])){
   header("Location:". APPURL . "login.php");
   exit();
}

// select
$user_id = $_SESSION['user']['id'];

// প্রথমে উইশলিস্ট থেকে ইউজারের পছন্দের প্রোডাক্ট আইডি বের করি
$wishlist_sql = "SELECT pro_id FROM wishlist WHERE user_id = $user_id";
$wishlist_result = $conn->query($wishlist_sql);

// উইশলিস্টে কিছু থাকলে ঐসব প্রোডাক্ট আনবে, না থাকলে fallback হিসেবে ইউজারের নিজের প্রোডাক্ট আনবে
$products = [];
$wishlist_product_ids = [];

if ($wishlist_result->num_rows > 0) {
    // উইশলিস্ট আইডি গুলো অ্যারে আকারে রাখি
    while($row = $wishlist_result->fetch_assoc()) {
        $wishlist_product_ids[] = $row['pro_id'];
    }

    // প্রোডাক্ট ডাটা গুলো আনবো যেখানে আইডি ইন উইশলিস্ট
    $ids = implode(',', $wishlist_product_ids);
    $product_sql = "SELECT * FROM product WHERE id IN ($ids)";
    $product_result = $conn->query($product_sql);
    $products = $product_result->fetch_all(MYSQLI_ASSOC);
}  


?>

        
        <!-- End Offset Wrapper -->
        <!-- Start Bradcaump area -->
        <div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(images/bg/4.jpg) no-repeat scroll center center / cover ;">
            <div class="ht__bradcaump__wrap">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="bradcaump__inner">
                                <nav class="bradcaump-inner">
                                  <a class="breadcrumb-item" href="index.php">Home</a>
                                  <span class="brd-separetor"><i class="zmdi-chevron-right zmdi"></i></span>
                                  <span class="breadcrumb-item active">Wishlist</span>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Bradcaump area -->
        <!-- wishlist-area start -->
        <div class="wishlist-area ptb--100 bg__white">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="wishlist-content">
                            <form action="#">
                                <div class="wishlist-table table-responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="product-remove"><span class="nobr">Remove</span></th>
                                                <th class="product-thumbnail">Image</th>
                                                <th class="product-name"><span class="nobr">Product Name</span></th>
                                                <th class="product-price"><span class="nobr"> Unit Price </span></th>
                                                <th class="product-stock-stauts"><span class="nobr"> Stock Status </span></th>
                                                <th class="product-add-to-cart"><span class="nobr">Add To Cart</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            foreach($products as $product){
                                                $wishlist_class = in_array($product['id'], $wishlist_product_ids) ? 'added' : '';

                                            ?>

                                            <tr>
                                                <td class="product-remove"><a href="delete.php?id=<?= $product['id']?>&categories_id=<?= $product['categories_id']?>" >×</a></td>
                                                <td class="product-thumbnail"><a href=""><img src="<?=APPURL ?>/admin-panel/images/product/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" /></a></td>
                                                <td class="product-name"><a href=""><?= $product['name'] ?></a></td>
                                                <td class="product-price"><span class="amount">$<?= $product['price'] ?></span></td>
                                                <td class="product-stock-status"><span class="wishlist-in-stock"> <?php 
        if($product['qty'] > 0){ 
            echo "In Stock (" . $product['qty'] . ")"; 
        } else { 
            echo "Out Stock"; 
        }  
        ?></span></td>
                                                <td class="product-add-to-cart"><a href="#"> Add to Cart</a></td>
                                            </tr>
                                            <?php }?>
                                           
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6">
                                                    <div class="wishlist-share">
                                                        <h4 class="wishlist-share-title">Share on:</h4>
                                                        <div class="social-icon">
                                                            <ul>
                                                                <li><a href="#"><i class="zmdi zmdi-rss"></i></a></li>
                                                                <li><a href="#"><i class="zmdi zmdi-vimeo"></i></a></li>
                                                                <li><a href="#"><i class="zmdi zmdi-tumblr"></i></a></li>
                                                                <li><a href="#"><i class="zmdi zmdi-pinterest"></i></a></li>
                                                                <li><a href="#"><i class="zmdi zmdi-linkedin"></i></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>  
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- wishlist-area end -->
        <!-- Start Brand Area -->
        <div class="htc__brand__area bg__cat--4">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="ht__brand__inner">
                            <ul class="clearfix brand__list owl-carousel">
                                <li><a href="#"><img src="images/brand/1.png" alt="brand images"></a></li>
                                <li><a href="#"><img src="images/brand/2.png" alt="brand images"></a></li>
                                <li><a href="#"><img src="images/brand/3.png" alt="brand images"></a></li>
                                <li><a href="#"><img src="images/brand/4.png" alt="brand images"></a></li>
                                <li><a href="#"><img src="images/brand/5.png" alt="brand images"></a></li>
                                <li><a href="#"><img src="images/brand/5.png" alt="brand images"></a></li>
                                <li><a href="#"><img src="images/brand/1.png" alt="brand images"></a></li>
                                <li><a href="#"><img src="images/brand/2.png" alt="brand images"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Brand Area -->
        <!-- Start Banner Area -->
        <div class="htc__banner__area">
            <ul class="clearfix banner__list owl-carousel owl-theme">
                <li><a href="product-details.html"><img src="images/banner/bn-3/1.jpg" alt="banner images"></a></li>
                <li><a href="product-details.html"><img src="images/banner/bn-3/2.jpg" alt="banner images"></a></li>
                <li><a href="product-details.html"><img src="images/banner/bn-3/3.jpg" alt="banner images"></a></li>
                <li><a href="product-details.html"><img src="images/banner/bn-3/4.jpg" alt="banner images"></a></li>
                <li><a href="product-details.html"><img src="images/banner/bn-3/5.jpg" alt="banner images"></a></li>
                <li><a href="product-details.html"><img src="images/banner/bn-3/6.jpg" alt="banner images"></a></li>
                <li><a href="product-details.html"><img src="images/banner/bn-3/1.jpg" alt="banner images"></a></li>
                <li><a href="product-details.html"><img src="images/banner/bn-3/2.jpg" alt="banner images"></a></li>
            </ul>
        </div>
        <!-- End Banner Area -->
        <!-- End Banner Area -->
        <!-- Start Footer Area -->
<?php include_once('includes/footer.php');?>
