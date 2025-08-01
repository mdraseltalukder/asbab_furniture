<?php 
include_once('includes/header.php');

if (!isset($_SESSION['user'])) {
    header("Location:" . APPURL . "/login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// ✅ Quantity Update Logic with Validation
$errors = [];

if (isset($_POST['update']) && isset($_POST['qty']) && is_array($_POST['qty'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        $id = (int)$id;
        $qty = (int)$qty;

        // Get product info
        $res = $conn->query("SELECT pro_id FROM add_to_cart WHERE id = $id AND user_id = $user_id");
        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $pro_id = (int)$row['pro_id'];

            // ✅ Get product stock
            $stock_sql = $conn->query("SELECT qty FROM product WHERE id = $pro_id");
            $stock_data = $stock_sql->fetch_assoc();
            $stock_qty = (int)$stock_data['qty'];

            // ✅ Get sold quantity
            $sold_sql = $conn->query("SELECT SUM(order_details.qty) as total_sold 
                                      FROM order_details 
                                      JOIN orders ON orders.id = order_details.order_id 
                                      WHERE orders.pro_id = $pro_id AND orders.order_status != 'Canceled'");
            $sold_row = $sold_sql->fetch_assoc();
            $sold_qty = (int)$sold_row['total_sold'];

            $in_stock_qty = $stock_qty - $sold_qty;

            if ($qty > $in_stock_qty) {
                $errors[] = "Product ID $pro_id has only $in_stock_qty available.";
                continue;
            }

            // ✅ Update if valid
            $res2 = $conn->query("SELECT price FROM add_to_cart WHERE id = $id AND user_id = $user_id");
            if ($res2->num_rows > 0) {
                $row2 = $res2->fetch_assoc();
                $price = $row2['price'];
                $total = $qty * $price;
                $conn->query("UPDATE add_to_cart SET qty = $qty, total = $total WHERE id = $id AND user_id = $user_id");
            }
        }
    }

    // ✅ Reload only if no errors
    if (empty($errors)) {
        header("Location: " . APPURL . "/cart.php");
        exit();
    }
}

// ✅ Fetch updated cart data
$select = "SELECT * FROM add_to_cart WHERE user_id = $user_id";
$result = $conn->query($select);
$carts = $result->fetch_all(MYSQLI_ASSOC);
?>

<!-- HTML Starts Here -->
<div class="cart-main-area ptb--100 bg__white">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <form method="POST" action="">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $e) echo "<p>$e</p>"; ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-content table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>products</th>
                                    <th>name</th>
                                    <th>price</th>
                                    <th>qty</th>
                                    <th>total</th>
                                    <th>remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($carts as $cart): ?>
                                    <tr>
                                        <td><img src="<?= APPURL ?>/admin-panel/images/product/<?= $cart['image'] ?>" alt="<?= $cart['name'] ?>" /></td>
                                        <td><?= htmlspecialchars($cart['name']) ?></td>
                                        <td>$<?= number_format($cart['price'], 2) ?></td>
                                        <td>
                                            <input type="number" name="qty[<?= $cart['id'] ?>]" value="<?= $cart['qty'] ?>" min="1" />
                                        </td>
                                        <td>$<?= number_format($cart['total'], 2) ?></td>
                                        <td><a href="delete.php?id=<?= $cart['id'] ?>"><i class="icon-trash icons"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="buttons-cart--inner">
                                <div class="buttons-cart">
                                    <a href="<?= APPURL ?>">Continue Shopping</a>
                                </div>
                                <div class="buttons-cart checkout--btn">
                                    <button type="submit" name="update">Update Cart</button>
                                    <a href="<?= APPURL ?>/checkout.php">Checkout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>
