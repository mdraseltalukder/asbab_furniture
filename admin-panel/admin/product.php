<?php 
include_once('../includes/header.php');
include_once('../../connection/conn.php');

if(!isset($_SESSION['admin'])){
   header("Location: login.php");
   exit();
}
// for multi vendor
$admin_id = $_SESSION['admin']['id'] ?? 0;
$role = $_SESSION['admin']['role'] ?? 0;

$condition2 = '';
$condition = '';
if ($role == 1) {
   // Vendor: show only own products
   $condition = "WHERE product.added_by = $admin_id";
   $condition2 = "AND added_by = $admin_id";
}




// Toggle Status
if(isset($_GET['type']) && $_GET['type'] == 'status' && isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $select = "SELECT status FROM product WHERE id = $id";
    $result = $conn->query($select);

    if ($result && $result->num_rows > 0) {
        $current_status = $result->fetch_assoc()['status'];
        $new_status = ($current_status == 1) ? 0 : 1;
        $conn->query("UPDATE product SET status = $new_status WHERE id = $id $condition2 ");
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// All Products
$selectall = "SELECT product.*, categories.categories  
              FROM product 
              LEFT JOIN categories ON product.categories_id = categories.id $condition ORDER BY product.id DESC";


$result = $conn->query($selectall);
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="pb-0 content">
   <div class="orders">
      <div class="row">
         <div class="col-xl-12">
            <div class="card">
               <div class="card-body">
                  <h4 class="box-title">Product</h4>
                  <a href="add-product.php" class="float-right badge badge-complete">Add product</a>
               </div>
               <div class="card-body--">
                  <div class="table-stats order-table ov-h">
                     <table class="table">
                        <thead>
                           <tr>
                              <th class="serial">#</th>
                              <th>name</th>
                              <th>category</th>
                              <th>image</th>
                              <th>mrp</th>
                              <th>price</th>
                              <th>stock</th>
                              <th>status</th>
                              <th>Best Seller</th>
                              <th>Edit</th>
                              <th>Delete</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach($products as $product): ?>
                              <?php 
                                  $product_id = (int)$product['id'];
                                  $stock_qty = (int)$product['qty'];

                                  // Sold qty
                                 $sold_sql = "SELECT SUM(order_details.qty) AS total_qty FROM order_details, orders WHERE order_details.product_id = $product_id AND order_details.order_id = orders.id AND orders.order_status != 'Canceled' AND((orders.payment_type='card' AND orders.payment_status='Success') OR (orders.payment_type='cod' AND orders.payment_status !=''))";
                                  $sold_result = $conn->query($sold_sql);
                                  $sold_qty = 0;
                                  if ($sold_result && $sold_result->num_rows > 0) {
                                      $row = $sold_result->fetch_assoc();
                                      $sold_qty = (int)($row['total_qty'] ?? 0);
                                  }

                                  $in_stock_qty = max(0, $stock_qty - $sold_qty);
                              ?>
                              <tr>
                                 <td class="serial"><?= $product['id'] ?></td>
                                 <td class="serial"><?= htmlspecialchars($product['name']) ?></td>
                                 <td class="serial"><?= htmlspecialchars($product['categories']) ?></td>
                                 <td class="serial">
                                    <img src="<?= ADMINURL ?>/images/product/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="50px" height="50px">
                                 </td>
                                 <td><span class="name">$<?= number_format($product['mrp'], 2) ?></span></td>
                                 <td><span class="name">$<?= number_format($product['price'], 2) ?></span></td>
                                 <td>
                                    <span class="name">
                                       <?= $stock_qty ?> 
                                       <small class="text-success">(In Stock: <?= $in_stock_qty ?>)</small>
                                    </span>
                                 </td>
                                 <td>
                                    <?php if($product['status']==1): ?>
                                       <span class="badge badge-complete">
                                          <a class="text-white" href="?type=status&id=<?= $product['id'] ?>">Active</a>
                                       </span>
                                    <?php else: ?>
                                       <span class="badge badge-pending">
                                          <a class="text-white" href="?type=status&id=<?= $product['id'] ?>">Inactive</a>
                                       </span>
                                    <?php endif; ?>
                                 </td>
                                 <td>
                                    <?= $product['best_seller'] == 1 ? '<span class="text-success">✔ Best Seller</span>' : '<span class="text-muted">Best Seller</span>' ?>
                                 </td>
                                 <td>
                                    <a class="bg-success pt-1 pr-2 pb-1 pl-2 rounded text-white" href="add-product.php?type=edit&id=<?= $product['id'] ?>">Edit</a>
                                 </td>
                                 <td>
                                    <a class="bg-danger pt-1 pr-2 pb-1 pl-2 rounded text-white" href="delete.php?id=<?= $product['id'] ?>&name=<?= urlencode($product['name']) ?>">Delete</a>
                                 </td>
                              </tr>
                           <?php endforeach; ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include_once('../includes/footer.php'); ?>
