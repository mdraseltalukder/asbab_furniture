<?php include_once('includes/header.php');
include_once('order_success_mail.php');


if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$email = $_SESSION['user']['email'];
$select = "SELECT * FROM add_to_cart WHERE user_id = $user_id";
$run = $conn->query($select);
$row = $run->fetch_all(MYSQLI_ASSOC);

if (!isset($_POST['pay']) && !isset($_POST['mihpayid'])) {
    if ($run->num_rows == 0) {
        header("location: index.php");
        exit;
    }
}

// Subtotal calculation
$subtotal = 0;
foreach ($row as $cart) {
    $subtotal += $cart['price'] * $cart['qty'];
}

// Step 1: Save address
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['address'])) {
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['city'] = $_POST['city'];
    $_SESSION['zip'] = $_POST['zip'];
    $_SESSION['open_tab'] = 'payment';
    header("Location: checkout.php");
    exit;
}

// Step 2: Payment handling
if (isset($_POST['pay'])) {
    $address = $_SESSION['address'];
    $city = $_SESSION['city'];
    $zip = $_SESSION['zip'];
    $payment_type = $_POST['payment_type'];
    $payment_status = $payment_type == 'cod' ? 'Success' : 'Pending';
    $order_status = 'Pending';

    $final_price = $_SESSION['final_price'] ?? null;
    $discount = $_SESSION['discount'] ?? null;
    $coupon_code = $_SESSION['coupon_code'] ?? null;
    $coupon_id = $_SESSION['coupon_id'] ?? null;
    $coupon_value = $_SESSION['coupon_value'] ?? null;
    $total = $final_price ?? $subtotal;

    $insert = "INSERT INTO orders (user_id, address, city, pincode, payment_type, total_price, payment_status, order_status, coupon_id, coupon_code, coupon_value) 
               VALUES ('$user_id', '$address', '$city', '$zip', '$payment_type', '$total', '$payment_status', '$order_status', '$coupon_id', '$coupon_code', '$discount')";
    $run = $conn->query($insert);

    if ($run) {
        $order_id = $conn->insert_id;

        foreach ($row as $cart) {
            $product_id = $cart['pro_id'];
            $qty = $cart['qty'];
            $total_single_price = $cart['price'] * $qty;

            $insert_detail = "INSERT INTO order_details (order_id, product_id, user_id, qty, total) 
                              VALUES ('$order_id', '$product_id', '$user_id', '$qty', '$total_single_price')";
            $conn->query($insert_detail);
        }

        $conn->query("DELETE FROM add_to_cart WHERE user_id = $user_id");

        unset($_SESSION['final_price'], $_SESSION['discount'], $_SESSION['coupon_code'], $_SESSION['coupon_id'], $_SESSION['coupon_value']);

        
        // ✅ Send order confirmation mail
        $select= "SELECT * FROM orders WHERE user_id = $user_id ORDER BY id DESC LIMIT 1";
        $result = $conn->query($select);
        $order = $result->fetch_assoc();
        $order_id = $order['id'];


     send_mail($email,$order_id);



        if ($payment_type === 'cod') {
            header("Location: success.php");
            exit;
        }

        // ✅ PayU Integration
        if ($payment_type === 'card') {
            include_once('payu-payment/payu_config.php');

            $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
            $amount = $total;
            $productinfo = "Order #$order_id";
            $firstname = $_SESSION['user']['name'];
            $hash_string = "$MERCHANT_KEY|$txnid|$amount|$productinfo|$firstname|$email|||||||||||$SALT";
            $hash = strtolower(hash('sha512', $hash_string));

            echo '
            <form action="'.$PAYU_BASE_URL.'/_payment" method="post" id="payuForm">
              <input type="hidden" name="key" value="'.$MERCHANT_KEY.'">
              <input type="hidden" name="txnid" value="'.$txnid.'">
              <input type="hidden" name="amount" value="'.$amount.'">
              <input type="hidden" name="productinfo" value="'.$productinfo.'">
              <input type="hidden" name="firstname" value="'.$firstname.'">
              <input type="hidden" name="email" value="'.$email.'">
              <input type="hidden" name="phone" value="'.$_SESSION['user']['phone'].'">
              <input type="hidden" name="surl" value="'.$SUCCESS_URL.'">
              <input type="hidden" name="furl" value="'.$FAILURE_URL.'">
              <input type="hidden" name="hash" value="'.$hash.'">
              <button type="submit">Pay Now</button>
            </form>
            <script>document.getElementById("payuForm").submit();</script>
            ';
            exit;
        }
    } else {
        $_SESSION['error'] = "Something went wrong while placing order.";
        header("Location: checkout.php");
        exit;
    }
}

// Step 3: Accordion tab toggle
$openTab = $_SESSION['open_tab'] ?? 'address';
unset($_SESSION['open_tab']);
?>



<div class="checkout-wrap ptb--100">
  <div class="container">
    <?php if (isset($_SESSION['error'])): ?>
      <div class='alert alert-danger'><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
      <div class='alert alert-success'><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div class="row" style="display: flex; gap: 20px;">
      <!-- Left Side -->
      <div class="col-md-8">
        <div class="checkout__inner">
          <div class="accordion-list">
            <div class="accordion">
              <!-- Address Accordion -->
              <div class="accordion__title" id="address-title" onclick="toggleAccordion('address-body')">
                Address Information
              </div>
              <div class="accordion__body" id="address-body" style="display: <?= $openTab === 'address' ? 'block' : 'none' ?>;">
                <div class="bilinfo">
                  <form id="address-form" method="POST" action="">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="single-input">
                          <input type="text" placeholder="Street Address" name="address" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="single-input">
                          <input type="text" placeholder="City/State" name="city" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="single-input">
                          <input type="text" placeholder="Post code/ zip" name="zip" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="single-input">
                          <button class="next_btn" type="submit" name="next">Next ></button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>

              <!-- Payment Accordion -->
              <div class="accordion__title" id="payment-title" onclick="toggleAccordion('payment-body')">
                Payment Information
              </div>
              <div class="accordion__body" id="payment-body" style="display: <?= $openTab === 'payment' ? 'block' : 'none' ?>;">
                <form action="" method="POST" id="payment-form">
                  <div>
                    <input type="radio" name="payment_type" id="cod" value="cod" required>
                    <label for="cod">Cash on Delivery</label>
                  </div>
                  <div>
                    <input type="radio" name="payment_type" id="card" value="card" required>
                    <label for="card">Card</label>
                  </div>
                  <button class="pay_btn" type="submit" name="pay">Pay</button>
                </form>
              </div>

            </div>
          </div>
        </div>
      </div>

      <!-- Right Side: Order Summary -->
      <div class="col-md-4">
        <div class="order-details">
          <h5 class="order-details__title">Your Order</h5>
          <div class="order-details__item">
            <?php foreach($row as $cart): ?>
            <div class="single-item">
              <div class="single-item__thumb">
                <img src="<?= APPURL ?>/admin-panel/images/product/<?= $cart['image'] ?>" alt="<?= $cart['name'] ?>"/>
              </div>
              <div class="single-item__content">
                <a href="#"><?= $cart['name'] ?></a>
                <span class="price">$<?= $cart['price'] ?> qty:<?= $cart['qty'] ?> </span>
              </div>
              <div class="single-item__remove">
                <a href="delete.php?id=<?= $cart['id'] ?>&user_id=<?= $_SESSION['user']['id'] ?>"><i class="icon-trash icons"></i></a>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
 
<?php 
    if(isset($discount)) {
?>
          <div class="order-details__count">
            <div class="order-details__count__single">
            <h5>Discount</h5>

<span class="price">
  $<?= $discount ?>
  
</span>


            
            </div>
          </div>
          <?php }?>

          <?php 
               $final_price = $_SESSION['final_price'] ?? null;

               $final_total= $final_price ?? $subtotal;
  $discount = $_SESSION['discount'] ?? null;
  ?>
          <div class="order-details__count">
            <div class="order-details__count__single">
            <h5>Order total</h5>

<span class="price">
  <?php if(isset($final_price) && $final_price < $subtotal): ?>
    <span style="text-decoration: line-through; color: gray;">
      $<?= number_format($subtotal, 2) ?>
    </span>
    &nbsp;
    <span style="color: green; font-weight: bold;">
      $<?= number_format($final_total, 2) ?>
    </span>
  <?php else: ?>
    <span style="font-weight: bold;">
      $<?= number_format($final_total, 2) ?>
    </span>
  <?php endif; ?>
</span>

            
            </div>
          </div>
          <div class="order-details__count">
            <form method="POST" action="set-coupon.php" class="order-details__count__single">
               <div class="single-input" style="height: 100%;margin-right: 10px;">
                         <input type="text" placeholder="Coupon Code" name="coupon_code" style="height: 100%; padding: 15px 5px; " required>
                        </div>
         
              <button class="pay_btn" type="submit" name="coupon">Apply Coupon</button>
            </form>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</div>

<?php 





