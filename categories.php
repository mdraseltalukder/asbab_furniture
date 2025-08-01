   <?php include_once('includes/header.php');



   // categories id er onke int chara kisu dile index e redirect korbe
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header("Location: index.php");
    exit();
}


//  sorting
$category_id= isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sort = isset($_POST['sort']) ? $_POST['sort'] : '';

$order_by = "ORDER BY id DESC"; 

if($sort == 'price_high'){
    $order_by = "ORDER BY price DESC";
} elseif($sort == 'price_low') {
    $order_by = "ORDER BY price ASC";
} elseif($sort == 'new') {
    $order_by = "ORDER BY id DESC";
} elseif($sort == 'old') {
    $order_by = "ORDER BY id ASC";
}

// products

$select= "SELECT * FROM product WHERE categories_id = $category_id $order_by";
$result=$conn->query($select);
    $products=$result->fetch_all(MYSQLI_ASSOC);

  
     
// wishlist 
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
                                  <span class="breadcrumb-item active">Categories</span>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if($result->num_rows == 0){
    echo "<h2 style='text-align: center; margin-top: 50px; color:red;'>No Product Found</h2>";
    exit();
}else{?>
  <section class="htc__category__area ptb--100">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="text-center section__title--2">
                            <h2 class="title__line">Products</h2>
                            <p>But I must explain to you how all this mistaken idea</p>
                        </div>
                         <div class="htc__select__option col-sm-4 col-md-2">
                           <form action="" method="POST">
    <select class="ht__select" name="sort" onchange="this.form.submit()">
        <option value="">Default sorting</option>
        <option value="price_high" <?= $sort == 'price_high' ? 'selected' : '' ?>>Price High</option>
        <option value="price_low" <?= $sort == 'price_low' ? 'selected' : '' ?>>Price Low</option>
        <option value="new" <?= $sort == 'new' ? 'selected' : '' ?>>Newest</option>
        <option value="old" <?= $sort == 'old' ? 'selected' : '' ?>>Oldest</option>
    </select>
  </form>
                                </div>
                    </div>
                    
                </div>
                <div class="htc__product__container">
                    <div class="row">
                        <div class="clearfix mt--30 product__list">
                            <?php foreach ($products as $product) {
$class = in_array($product['id'], $wishlist_product_ids) ? 'added' : '';

                          
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
        <?php }?>


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

        
<?php 
include_once('includes/footer.php');
?>