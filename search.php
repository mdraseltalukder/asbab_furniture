   <?php include_once('includes/header.php');



// search 
$search = isset($_GET['str']) ? trim($_GET['str']) : '';

if(!empty($search)){
    $search_safe = $conn->real_escape_string($search); // SQL Injection protection
    $select = "SELECT * FROM product WHERE name LIKE '%$search_safe%' OR sort_desc LIKE '%$search_safe%' ORDER BY id DESC";
} else {

    $select = "SELECT * FROM product ORDER BY id DESC";
}

$run = $conn->query($select);
$products = $run->fetch_all(MYSQLI_ASSOC);


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


       <div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(images/bg/4.jpg) no-repeat scroll center center / cover ;">
            <div class="ht__bradcaump__wrap">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="bradcaump__inner">
                                <nav class="bradcaump-inner">
                                  <a class="breadcrumb-item" href="<?= APPURL ?>">Home</a>
                                  <span class="brd-separetor"><i class="zmdi-chevron-right zmdi"></i></span>
                                  <span class="breadcrumb-item active"><?= $search ?></span>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
  <section class="htc__category__area ptb--100">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="text-center section__title--2">
                            <?php if (empty($products)){ ?>
  <h2 style="text-align: center; margin-top: 50px; color:red;">
    No Product Found<?= $search_safe ? ' for "' . htmlspecialchars($search_safe) . '"' : '' ?>
  </h2>
<?php }else{ ?>
                            <h2 class="title__line">Search For <span class="textGreen title__line">"<?=$search_safe ?>"</span></h2>
                <?php }?>        


                        </div>
                         
                    </div>
                    
                </div>
                <div class="htc__product__container">
                    <div class="row">
                        <div class="clearfix mt--30 product__list">
                            <?php foreach ($products as $product) {
                          
                            ?>
                            <div class="col-md-4 col-lg-3 col-sm-4 col-xs-12">
                                <div class="category">
                                    <div class="ht__cat__thumb">
                                        <a href="product-details.php?id=<?=$product['id'] ?>&categories_id=<?=$product['categories_id'] ?>">
                                            <img src="<?= APPURL ?>/admin-panel/images/product/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                                        </a>
                                    </div>
                                    <div class="fr__hover__info">
                                        <ul class="product__action">
                                             <li>
    <a href="#" class="add-to-wishlist <?= $class ?>" data-id="<?= $product['id']; ?>">
      <i class="icon-heart icons"></i>
    </a>
  </li>

                                           
                                        </ul>
                                    </div>
                                    <div class="fr__product__inner">
                                        <h4><a href="product-details.php?id=<?=$product['id'] ?>&categories_id=<?=$product['categories_id'] ?>"><?= $product['name'] ?></a></h4>
                                        <span><?= $product['sort_desc'] ?></span>
                                        <ul class="fr__pro__prize">
                                            <li class="old__prize">$<?= $product['mrp'] ?></li>
                                            <li>$<?= $product['price'] ?></li>
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
       

<?php 
include_once('includes/footer.php');
?>

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