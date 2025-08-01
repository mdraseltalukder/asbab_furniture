     <?php include_once('includes/header.php');
     



// product new arrival
$select="SELECT * FROM product Order BY id DESC LIMIT 8";
$result=$conn->query($select);
$New_arrivals=$result->fetch_all(MYSQLI_ASSOC);
// best seller
$select_best="SELECT * FROM product WHERE best_seller=1 Order BY id DESC LIMIT 4";
$result_query=$conn->query($select_best);
$best_seller=$result_query->fetch_all(MYSQLI_ASSOC);



// wishlict 
$wishlist_product_ids = [];

if (isset($user_id) && $user_id > 0) {
    $wish_res = $conn->query("SELECT pro_id FROM wishlist WHERE user_id = $user_id");
    if ($wish_res->num_rows > 0) {
        while ($row = $wish_res->fetch_assoc()) {
            $wishlist_product_ids[] = $row['pro_id'];
        }
    }
}




  
     ?>
     <style>
        .product__action li a.added {
background-color: #c43b68;

}
        .product__action li a.added i {
  color: white;
}

     </style>


        <!-- End Header Area -->

        
        <!-- End Offset Wrapper -->
        <!-- Start Slider Area -->
        <div class="slider__container slider--one bg__cat--3">
            <div class="slide__container slider__activation__wrap owl-carousel">
                <!-- Start Single Slide -->
                <div class="single__slide animation__style01 slider__fixed--height">
                    <div class="container">
                        <div class="row align-items__center">
                            <div class="col-md-7 col-sm-7 col-xs-12 col-lg-6">
                                <div class="slide">
                                    <div class="slider__inner">
                                        <h2>collection 2018</h2>
                                        <h1>NICE CHAIR</h1>
                                       
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-5 col-xs-12 col-md-5">
                                <div class="slide__thumb">
                                    <img src="images/slider/fornt-img/1.png" alt="slider images">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Single Slide -->
                <!-- Start Single Slide -->
                <div class="single__slide animation__style01 slider__fixed--height">
                    <div class="container">
                        <div class="row align-items__center">
                            <div class="col-md-7 col-sm-7 col-xs-12 col-lg-6">
                                <div class="slide">
                                    <div class="slider__inner">
                                        <h2>collection 2018</h2>
                                        <h1>NICE CHAIR</h1>
                                      
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-5 col-xs-12 col-md-5">
                                <div class="slide__thumb">
                                    <img src="images/slider/fornt-img/2.png" alt="slider images">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Single Slide -->
            </div>
        </div>
        <!-- Start Slider Area -->
        <!-- Start Category Area -->
        <section class="htc__category__area ptb--100">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="text-center section__title--2">
                            <h2 class="title__line">New Arrivals</h2>
                            <p>But I must explain to you how all this mistaken idea</p>
                        </div>
                    </div>
                </div>
                <div class="htc__product__container">
                    <div class="row">
                        <div class="clearfix mt--30 product__list">
                            <?php foreach ($New_arrivals as $new_arrival) {
                                $class = in_array($new_arrival['id'], $wishlist_product_ids) ? 'added' : '';
                          
                            ?>
                            <div class="col-md-4 col-lg-3 col-sm-4 col-xs-12">
                                <div class="category">
                                    <div class="ht__cat__thumb">
                                        <a href="product-details.php?id=<?=$new_arrival['id'] ?>&categories_id=<?=$new_arrival['categories_id'] ?>">
                                            <img src="<?= APPURL ?>/admin-panel/images/product/<?= $new_arrival['image'] ?>" alt="<?= $new_arrival['name'] ?>">
                                        </a>
                                    </div>
                                    <div class="fr__hover__info">
                                        <ul class="product__action">
                                                                                 <li>
    <a href="javascript:void(0);" class="add-to-wishlist <?= $class ?>" data-id="<?= $new_arrival['id']; ?>">
      <i class="icon-heart icons"></i>
    </a>
  </li>

                                          
                                        </ul>
                                    </div>
                                    <div class="fr__product__inner">
                                        <h4><a href="product-details.php?id=<?=$new_arrival['id'] ?>&categories_id=<?=$product['categories_id'] ?>"><?= $new_arrival['name'] ?></a></h4>
                                        <span class="old_prize"><?= $new_arrival['sort_desc'] ?></span>
                                        <ul class="fr__pro__prize">
                                            <li class="old__prize">$<?= $new_arrival['mrp'] ?></li>
                                            <li>$<?= $new_arrival['price'] ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                          
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Category Area -->
        <!-- Start Product Area -->
        <section class="ftr__product__area ptb--100">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="text-center section__title--2">
                            <h2 class="title__line">Best Seller</h2>
                            <p>But I must explain to you how all this mistaken idea</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                        <div class="clearfix mt--30 product__list">
                            <?php foreach ($best_seller as $best) {
                                $class = in_array($best['id'], $wishlist_product_ids) ? 'added' : '';
                          
                            ?>
                            <div class="col-md-4 col-lg-3 col-sm-4 col-xs-12">
                                <div class="category">
                                    <div class="ht__cat__thumb">
                                        <a href="product-details.php?id=<?=$best['id'] ?>&categories_id=<?=$best['categories_id'] ?>">
                                            <img src="<?= APPURL ?>/admin-panel/images/product/<?= $best['image'] ?>" alt="<?= $best['name'] ?>">
                                        </a>
                                    </div>
                                    <div class="fr__hover__info">
                                        <ul class="product__action">
                                                                                 <li>
    <a href="javascript:void(0);" class="add-to-wishlist <?= $class ?>" data-id="<?= $best['id']; ?>">
      <i class="icon-heart icons"></i>
    </a>
  </li>

                                          
                                        </ul>
                                    </div>
                                    <div class="fr__product__inner">
                                        <h4><a href="product-details.php?id=<?=$best['id'] ?>&categories_id=<?=$best['categories_id'] ?>"><?= $best['name'] ?></a></h4>
                                        <span class="old_prize"><?= $best['sort_desc'] ?></span>
                                        <ul class="fr__pro__prize">
                                            <li class="old__prize">$<?= $best['mrp'] ?></li>
                                            <li>$<?= $best['price'] ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                          
                        </div>
                    </div>
            </div>
        </section>
        <!-- End Product Area -->
        <!-- Start Footer Area -->
     <?php include_once('includes/footer.php');?>

     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('.add-to-wishlist').on('click', function(e) {
    e.preventDefault();
    const $this = $(this); // ✅ এখন ঠিকমতো define করা হলো
    const productId = $this.data('id');

    $.ajax({
        url: 'ajax/add_to_wishlist.php',
        method: 'POST',
        data: { product_id: productId },
        success: function(response) {
            
            if (response.trim() === 'login_required') {
                window.location.href = "login.php";
            } else {
                alert(response);
            }

            if (response.trim() === 'Added to wishlist!') {
                $this.addClass('added');
            }

            if (response.trim() === 'Removed from wishlist.') {
                $this.removeClass('added');
            }
        }
    });
});
</script>
<script>
$(document).ready(function() {
  const productId = localStorage.getItem('wishlist_add_after_login');

  if (productId) {
    localStorage.removeItem('wishlist_add_after_login');

    $.ajax({
      url: 'ajax/add_to_wishlist.php',
      method: 'POST',
      data: { product_id: productId },
      success: function(response) {
        alert(response);
        // ✅ ইচ্ছা করলে class add করে দিতে পারো
        $(`[data-id="${productId}"]`).addClass('added');
        updateWishlistCount(); // ✅ Count update
      }
    });
  }
});
</script>

