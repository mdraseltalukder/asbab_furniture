<?php 

include_once('includes/header.php');

// Validate GET parameters
if (!isset($_GET['categories_id']) || !filter_var($_GET['categories_id'], FILTER_VALIDATE_INT)) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header("Location: index.php");
    exit();
}

$cat_id = (int) $_GET['categories_id'];
$product_id = (int) $_GET['id'];

// Handle Add to Cart POST with PRG pattern
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Please login to add to cart.";
        header("Location: login.php");
        exit();
    }

    $qty = (int) $_POST['qty'];
    if ($qty <= 0) {
        $_SESSION['error'] = "Invalid quantity selected.";
        header("Location: product-details.php?id=$product_id&categories_id=$cat_id");
        exit();
    }

    // Fetch product info securely
    $product_sql = "SELECT * FROM product WHERE id = $product_id LIMIT 1";
    $product_res = $conn->query($product_sql);
    if ($product_res && $product_res->num_rows > 0) {
        $product_data = $product_res->fetch_assoc();

        $name = $conn->real_escape_string($product_data['name']);
        $image = $conn->real_escape_string($product_data['image']);
        $price = (float) $product_data['price'];
        $total = $qty * $price;

        $user_id = (int) $_SESSION['user']['id'];

        // Check if product already in cart for this user
        $check_sql = "SELECT * FROM add_to_cart WHERE pro_id = $product_id AND user_id = $user_id";
        $check_res = $conn->query($check_sql);

        if ($check_res->num_rows == 0) {
            $insert_sql = "INSERT INTO add_to_cart (name, image, price, qty, total, pro_id, user_id) 
                           VALUES ('$name', '$image', $price, $qty, $total, $product_id, $user_id)";
            if ($conn->query($insert_sql)) {
                $_SESSION['success'] = "Product added to cart successfully.";
            } else {
                $_SESSION['error'] = "Failed to add product to cart.";
            }
        } else {
            $_SESSION['success'] = "Product already added to cart.";
        }
    } else {
        $_SESSION['error'] = "Product not found.";
    }

    // Redirect to avoid form resubmission
    header("Location: product-details.php?id=$product_id&categories_id=$cat_id");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_now'])) {
    if (!isset($_SESSION['user'])) {
        $_SESSION['error'] = "Please login to add to cart.";
        header("Location: login.php");
        exit();
    }

    $qty = (int) $_POST['qty'];
    if ($qty <= 0) {
        $_SESSION['error'] = "Invalid quantity selected.";
        header("Location: product-details.php?id=$product_id&categories_id=$cat_id");
        exit();
    }

    // Fetch product info securely
    $product_sql = "SELECT * FROM product WHERE id = $product_id LIMIT 1";
    $product_res = $conn->query($product_sql);
    if ($product_res && $product_res->num_rows > 0) {
        $product_data = $product_res->fetch_assoc();

        $name = $conn->real_escape_string($product_data['name']);
        $image = $conn->real_escape_string($product_data['image']);
        $price = (float) $product_data['price'];
        $total = $qty * $price;

        $user_id = (int) $_SESSION['user']['id'];

        // Check if product already in cart for this user
        $check_sql = "SELECT * FROM add_to_cart WHERE pro_id = $product_id AND user_id = $user_id";
        $check_res = $conn->query($check_sql);

        if ($check_res->num_rows == 0) {
            $insert_sql = "INSERT INTO add_to_cart (name, image, price, qty, total, pro_id, user_id) 
                           VALUES ('$name', '$image', $price, $qty, $total, $product_id, $user_id)";
            if ($conn->query($insert_sql)) {
                header("Location: checkout.php");
                exit();
            } else {
                $_SESSION['error'] = "Failed to add product to cart.";
            }
        } else {
            $_SESSION['success'] = "Product already added to cart.";
        }
    } else {
        $_SESSION['error'] = "Product not found.";
    }

    // Redirect to avoid form resubmission
    header("Location: product-details.php?id=$product_id&categories_id=$cat_id");
    exit();
}



// Fetch single product details
$product_sql = "SELECT product.*, categories.categories AS category_name 
                FROM product 
                LEFT JOIN categories ON product.categories_id = categories.id 
                WHERE product.id = $product_id
                ORDER BY product.id DESC";
$product_result = $conn->query($product_sql);
$product = $product_result ? $product_result->fetch_assoc() : null;

if (!$product) {
    header("Location: index.php");
    exit();
}

// Fetch relative products
$rel_sql = "SELECT * FROM product WHERE categories_id = $cat_id AND id != $product_id ORDER BY id DESC";
$rel_result = $conn->query($rel_sql);
$relative_products = $rel_result ? $rel_result->fetch_all(MYSQLI_ASSOC) : [];

// Check if product is already in cart for disabling button
$add_to_cart_text = "Add to Cart";
$disable = false;
if (isset($_SESSION['user'])) {
    $user_id = (int) $_SESSION['user']['id'];
    $check_cart_sql = "SELECT * FROM add_to_cart WHERE pro_id = $product_id AND user_id = $user_id";
    $check_cart_res = $conn->query($check_cart_sql);
    if ($check_cart_res && $check_cart_res->num_rows > 0) {
        $add_to_cart_text = "Added to Cart";
        $disable = true;
    }
}

// check how much product sold
$sold_sql = "SELECT SUM(order_details.qty) AS total_qty FROM order_details, orders WHERE order_details.product_id = $product_id AND order_details.order_id = orders.id AND orders.order_status != 'Canceled' AND((orders.payment_type='card' AND orders.payment_status='Success') OR (orders.payment_type='cod' AND orders.payment_status !=''))";
$sold_sql_result = $conn->query($sold_sql);

$row = $sold_sql_result->fetch_assoc() ;
$sold_qty=$row['total_qty'];
// echo " Sold Quantity: $sold_qty"; 

// cheh how much product in stock
$stock_sql = "SELECT qty FROM product WHERE id = $product_id";
$stock_sql_result = $conn->query($stock_sql);
$stock_row = $stock_sql_result->fetch_assoc();
$stock_qty= $stock_row['qty'];


$in_stock_qty = $stock_qty - $sold_qty;
// echo " In Stock Quantity: $in_stock_qty";

     
// wishlist 
$wishlist_product_ids = [];

    $user_id = (int) $_SESSION['user']['id'];
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


<!-- HTML STARTS HERE -->

<!-- Breadcrumb -->
<div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(images/bg/4.jpg) no-repeat scroll center center / cover ;">
    <div class="ht__bradcaump__wrap">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="bradcaump__inner">
                        <nav class="bradcaump-inner">
                            <a class="breadcrumb-item" href="<?= APPURL ?>">Home</a>
                            <span class="brd-separetor"><i class="zmdi-chevron-right zmdi"></i></span>
                            <a class="breadcrumb-item" href="categories.php?id=<?= $product['categories_id'] ?>"><?= htmlspecialchars($product['category_name']) ?></a>
                            <span class="brd-separetor"><i class="zmdi-chevron-right zmdi"></i></span>
                            <span class="breadcrumb-item active"><?= htmlspecialchars($product['name']) ?></span>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Details Section -->
<section class="htc__product__details bg__white ptb--100">
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-lg-5 col-sm-12 col-xs-12">
                <div class="htc__product__details__tab__content">
                    <div class="product__big__images">
                        <div class="portfolio-full-image tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="img-tab-1">
                                <img src="<?= APPURL ?>/admin-panel/images/product/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 col-lg-7 col-sm-12 col-xs-12 smt-40 xmt-40">
                <div class="ht__product__dtl">
                    <h2><?= htmlspecialchars($product['name']) ?></h2>
                    <ul class="pro__prize">
                        <li class="old__prize">$<?= number_format($product['mrp'], 2) ?></li>
                        <li>$<?= number_format($product['price'], 2) ?></li>
                    </ul>
                    <p class="pro__info"><?= htmlspecialchars($product['sort_desc']) ?></p>
                    <div class="ht__pro__desc">
                        <div class="sin__desc">
                            <p><span>Availability:</span> <?= ($stock_qty > $sold_qty) ? 'In Stock' : 'Out of Stock'; ?></p>
                        </div>
                        <div class="align--left sin__desc">
                            <p><span>Categories:</span></p>
                            <ul class="pro__cat__list">
                                <li><a href="categories.php?id=<?= $product['categories_id'] ?>"><?= htmlspecialchars($product['category_name']) ?></a></li>
                            </ul>
                        </div>
<?php if($stock_qty > $sold_qty){
    $max_qty = min($in_stock_qty, 10); 
    ?>
                        <form action="" method="POST">
                            <div class="align--left sin__desc">
                                <p><span>Quantity:</span></p>
                                <select name="qty" class="col-sm-3 qty_select">
                                    <?php for ($i=1; $i <= $max_qty; $i++) : ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <?php 
                            if (isset($_SESSION['error'])) {
                                echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
                                unset($_SESSION['error']);
                            }
                            if (isset($_SESSION['success'])) {
                                echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
                                unset($_SESSION['success']);
                            }
                            ?>

                            <div class="cr__btn margin_top">
                                <?php if (isset($_SESSION['user'])) : ?>
                                    <button type="submit" name="add_to_cart" <?= $disable ? 'disabled style="background:#750027; cursor:not-allowed;"' : '' ?>>
                                        <?= $add_to_cart_text ?>
                                    </button>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-primary">Login to Add to Cart</a>
                                <?php endif; ?>

                                <button id="buy_now" name="buy_now">Buy Now</button>
                            </div>
                        </form>
                        <?php }?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Description -->
<section class="htc__produc__decription bg__white">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ul class="pro__details__tab" role="tablist">
                    <li role="presentation" class="description active"><a href="#description" role="tab" data-toggle="tab">description</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="ht__pro__details__content">
                    <div role="tabpanel" id="description" class="pro__single__content tab-pane fade in active">
                        <div class="pro__tab__content__inner">
                            <p><?= nl2br(htmlspecialchars($product['desc'])) ?></p>
                        </div>
                    </div>   
                </div>
            </div>
        </div>
    </div>
</section>

<!-- recent view Products -->

<section class="pb--100 htc__product__area--2 product-details-res">
      <?php 
        // unset($_COOKIE['recently_viewed']);
      
      if(isset($_COOKIE['recently_viewed'])){ 
        


                    


$arrRecentViewed = unserialize($_COOKIE['recently_viewed']);


$arrRecentViewedId=implode(',', $arrRecentViewed);// , diye nilam (1 2) ke (1,2) korlam

$relative_sql = "SELECT * FROM product WHERE id IN ($arrRecentViewedId) ORDER BY FIELD(id, $arrRecentViewedId) DESC LIMIT 4";
$relative_result = $conn->query($relative_sql);
if ($relative_result && $relative_result->num_rows > 0) {
    $relative_products = $relative_result->fetch_all(MYSQLI_ASSOC);
}


        ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="text-center section__title--2">
                  
                    <h2 class="title__line">Recently Viewed</h2>
                  
                </div>
            </div>
        </div>

        <?php if (!empty($relative_products)) : ?>
        <div class="row">
            <div class="clearfix product__wrap">
                <?php foreach ($relative_products as $rel_prod) :
                   
                    ?>

                    <div class="col-md-4 col-lg-3 col-sm-4 col-xs-12">
                        <div class="category">
                            <div class="ht__cat__thumb">
                                <a href="product-details.php?id=<?= $rel_prod['id'] ?>&categories_id=<?= $rel_prod['categories_id'] ?>">
                                    <img src="<?= APPURL ?>/admin-panel/images/product/<?= htmlspecialchars($rel_prod['image']) ?>" alt="<?= htmlspecialchars($rel_prod['name']) ?>">
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
                                <h4><a href="product-details.php?id=<?= $rel_prod['id'] ?>&categories_id=<?= $rel_prod['categories_id'] ?>"><?= htmlspecialchars($rel_prod['name']) ?></a></h4>
                                <span class="old_prize"><?= htmlspecialchars($rel_prod['sort_desc']) ?></span>
                                <ul class="fr__pro__prize">
                                    <li class="old__prize">$<?= number_format($rel_prod['mrp'], 2) ?></li>
                                    <li>$<?= number_format($rel_prod['price'], 2) ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php else: ?>
            <h2 style="text-align: center; margin-top: 50px; color:red;">No Relative Product Found</h2>
        <?php endif; ?>
    </div>
            <?php
        $cookie_arr=unserialize($_COOKIE['recently_viewed'] );
                  if(($key=array_search($product_id, $cookie_arr))!==false){
                      unset($cookie_arr[$key]);
                  }        
                    $cookie_arr[]=$product_id;
                    setcookie("recently_viewed", serialize($cookie_arr), time()+60*60*24*365);    
        
        
        
        }else{
 
                  $cookie_arr[]=$product_id;

                    // unset($_COOKIE['recently_viewed']);
                    setcookie("recently_viewed", serialize($cookie_arr), time()+60*60*24*365);
                    
                    
                }?>

</section>

<?php include_once('includes/footer.php'); ?>
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