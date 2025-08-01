// success.php

<?php
session_start();

// unset coupon after order
unset($_SESSION['final_price']);
unset($_SESSION['discount']);
unset($_SESSION['coupon_id']);
unset($_SESSION['coupon_code']);
unset($_SESSION['coupon_value']);

// ✅ Redirect to a clean success message page
header("Location: thanks.php");
exit;
?>
