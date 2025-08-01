<?php 
include_once('../includes/header.php');
include_once('../../connection/conn.php');

if(!isset($_SESSION['admin'])){
   header("Location: login.php");
   exit();
}
if(!isset($_SESSION['admin']['role']) || $_SESSION['admin']['role'] != 0){
   header("Location: product.php");
   exit();
}




// Toggle Status
if(isset($_GET['type']) && $_GET['type'] == 'status' && isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $select = "SELECT status FROM product WHERE id = $id";
    $result = $conn->query($select);

    if ($result && $result->num_rows > 0) {
        $current_status = $result->fetch_assoc()['status'];
        $new_status = ($current_status == 1) ? 0 : 1;
        $conn->query("UPDATE product SET status = $new_status WHERE id = $id");
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// All Products
$selectall = "SELECT * FROM coupon";

$result = $conn->query($selectall);
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="pb-0 content">
   <div class="orders">
      <div class="row">
         <div class="col-xl-12">
            <div class="card">
               <div class="card-body">
                  <h4 class="box-title">Coupon</h4>
                  <a href="add-coupon.php" class="float-right badge badge-complete">Add Coupon</a>
               </div>
               <div class="card-body--">
                  <div class="table-stats order-table ov-h">
                     <table class="table">
                        <thead>
                           <tr>
                              <th class="serial">#</th>
                              <th>Coupon Code</th>
                              <th>Coupon value</th>
                              <th>Coupon type</th>
                              <th>Cart Min Value</th>
                              <th>Status</th>
                              
                              <th>Edit</th>
                              <th>Delete</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach($products as $product): ?>
                              <?php 
                                  $product_id = (int)$product['id'];
        

                                
                              ?>
                              <tr>
                                 <td class="serial"><?= $product['id'] ?></td>
                                 <td class="serial"><?= htmlspecialchars($product['coupon_code']) ?></td>
                                 <td class="serial"><?= htmlspecialchars($product['coupon_value']) ?></td>
                                 
                                 <td><span class="name"><?= ($product['coupon_type']) ?></span></td>
                                 <td><span class="name">$<?= number_format($product['cart_min_value']) ?></span></td>
       <td>
  <span style="
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-weight: bold;
    font-size: 14px;
    color: #fff;
    background-color: <?= $product['coupon_status'] == 1 ? '#28a745' : '#dc3545' ?>;
  ">
    <?= $product['coupon_status'] == 1 ? 'Active' : 'Inactive' ?>
  </span>
</td>

                              
                                 <td>
                                    <a class="bg-success pt-1 pr-2 pb-1 pl-2 rounded text-white" href="add-coupon.php?type=edit&id=<?= $product['id'] ?>">Edit</a>
                                 </td>
                                 <td>
                                    <a class="bg-danger pt-1 pr-2 pb-1 pl-2 rounded text-white" href="delete.php?id=<?= $product['id'] ?>&coupon=<?= urlencode($product['coupon_code']) ?>">Delete</a>
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
