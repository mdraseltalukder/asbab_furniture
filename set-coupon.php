<?php 
session_start();
include_once('connection/conn.php');

if(isset($_POST['coupon'])) {
    $coupon_code = $_POST['coupon_code'];

    $coupon_query = "SELECT * FROM coupon WHERE BINARY coupon_code = '$coupon_code'";
    $coupon_result = mysqli_query($conn, $coupon_query);

    if(mysqli_num_rows($coupon_result) > 0) {
        $coupon = mysqli_fetch_assoc($coupon_result);
        $coupon_value = $coupon['coupon_value'];
        $coupon_type = $coupon['coupon_type'];
        $cart_min_value = $coupon['cart_min_value'];
        $coupon_id = $coupon['id'];

        // total
        $user_id = $_SESSION['user']['id'];
        $select = "SELECT * FROM add_to_cart WHERE user_id = $user_id";
        $run = $conn->query($select);
        $row = $run->fetch_all(MYSQLI_ASSOC);

        if ($run->num_rows == 0) {
            header("location: index.php");
            exit;
        } else {
            $subtotal = 0;
            foreach ($row as $cart) {
                $subtotal += $cart['price'] * $cart['qty'];
            }
        }

        if($subtotal < $cart_min_value) {
            $_SESSION['error'] = "Cart value must be at least $$cart_min_value to apply this coupon.";
            header("Location: checkout.php");
            exit();
        } else {
            if ($coupon_type == '%') {
                $discount = ($subtotal * $coupon_value) / 100;
            } else {
                $discount = $coupon_value;
            }

            $final_price = $subtotal - $discount;

            $_SESSION['final_price'] = $final_price;
            $_SESSION['discount'] = $discount;
            $_SESSION['coupon_id'] = $coupon_id;
            $_SESSION['coupon_code'] = $coupon_code;
            $_SESSION['coupon_value'] = $coupon_value;
        }

        header("Location: checkout.php");
        exit();

    } else {
        // ✅ Invalid coupon
        $_SESSION['error'] = "Invalid coupon code.";
        header("Location: checkout.php");
        exit();
    }
}
?>
