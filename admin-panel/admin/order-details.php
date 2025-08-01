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
 
$order_id = $_GET['id']; // single order details page er order_id

$select = "
SELECT 
    order_details.*,
    product.name AS product_name,
    product.price AS product_price,
    product.image AS product_image,
    orders.total_price AS order_total_price,
    orders.coupon_value AS order_coupon_value
FROM 
    order_details
JOIN 
    orders ON orders.id = order_details.order_id
JOIN 
    product ON order_details.product_id = product.id
WHERE 
     order_details.order_id = $order_id
ORDER BY 
    order_details.id DESC
";

$result = $conn->query($select);
$orders = $result->fetch_all(MYSQLI_ASSOC);

$subtotal = 0; // Initialize subtotal variable



// address
$Orders_query= "SELECT address, city, pincode,order_status FROM orders WHERE id = $order_id";
$address_result = $conn->query($Orders_query);
$order_add = $address_result->fetch_all(MYSQLI_ASSOC);

$address = $order_add[0]['address'] ?? '';
$city = $order_add[0]['city'] ?? '';
$pincode = $order_add[0]['pincode'] ?? '';
 $order_status = $order_add[0]['order_status'] ?? '';



//  order_status
$select= "SELECT * FROM order_status";
$result_status = $conn->query($select);
$order_statuses = $result_status->fetch_all(MYSQLI_ASSOC);


// update order status
if(isset($_POST['update'])) {
$order_status = $_POST['order_status'];
$update= "UPDATE orders SET order_status = '$order_status' WHERE id = $order_id";
$conn->query($update);
header("Location: order.php");
exit();



}


 ?>
 
<style>
.subtotal-wrapper {
  width: 100%;
  display: flex;
  justify-content: flex-end;
  margin-top: 30px;
  padding-right: 20px;
}

.subtotal-box {
  background-color: #f5f5f5;
  border: 1px solid #ddd;
  border-radius: 10px;
  padding: 20px 30px;
  text-align: right;
  max-width: 300px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.subtotal-box h5 {
  margin: 0;
  font-size: 16px;
  color: #555;
}

.subtotal-box h4 {
  margin: 5px 0 0;
  font-size: 22px;
  color: #28a745; /* green */
}
</style>



<div class="pb-0 content">
   <div class="orders">
      <div class="row">
         <div class="col-xl-12">
            <div class="card">
               <div class="card-body">
                  <h4 class="box-title">Order Details</h4>
               
 <form method="POST" action="">
                                     
                              
  <div class="card-body--">
                  <div class="table-stats order-table ov-h">
                     <table class="table">
        <thead>
           <tr>
               <th>Product Name</th>
               <th>Product Image</th>
               <th>Quantity</th>
               <th> Single price</th>
               <th>Total Price</th>
               
           </tr>
       </thead>
        <tbody>
           <?php foreach($orders as $order){
         $subtotal = $order['order_total_price'] ;
         
            ?>
           <tr>
               <td><?= $order['product_name'] ?></td>
               <td><img src="<?= ADMINURL ?>/images/product/<?= $order['product_image'] ?>" alt="<?= $order['product_name'] ?>"></td>
               <td><?= $order['qty'] ?></td>
               <td>$<?= $order['product_price'] ?></td>
               <td>$<?= $order['total'] ?></td>
              
               
           </tr>
           <?php }?>
           <tr>
               <td></td>
               <td></td>
               <td></td>
               <td>Discount :</td>
               
                <td> $<?= $order['order_coupon_value'] ?></td>
               
               
                
            </tr>
              <tr>
               <td></td>
               <td></td>
               <td></td>
               <td>Sub Total Price:</td>
              
                <td> $<?= $order['order_total_price'] ?></td>
               
               
                
            </tr>
          
       </tbody>
    </table>

    <!-- subtotal section -->
<div class="subtotal-wrapper">
  <div class="subtotal-box">
    <h5>Subtotal:</h5>
    <h4>$<?= number_format($subtotal, 2) ?></h4>
  </div>
</div>


<div class="mt-4">
  <strong>Shipping Address:</strong><br>
  <?= htmlspecialchars($address) ?>, 
  <?= htmlspecialchars($city) ?>, 
  <?= htmlspecialchars($pincode) ?>
</div>
<div class="mt-4">
  <strong>Order Status:</strong><br>
  <?PHP 
  
  if(htmlspecialchars($order_status)=='Pending') {
    echo '<span class="badge badge-warning">Pending</span>';
  } elseif(htmlspecialchars($order_status)=='Complete') {
    echo '<span class="badge badge-success">Completed</span>';
  } elseif(htmlspecialchars($order_status)=='Canceled') {
    echo '<span class="badge badge-danger">Cancelled</span>';
  }elseif(htmlspecialchars($order_status)=='Processing') {
    echo '<span class="badge badge-info">Processing</span>';
  }else {
    echo '<span class="badge badge-primary">Shipped</span>';
  }
  
  ?>
  


  <div>
  <form action="" method="POST">
    <select name="order_status" id="">
      <?php foreach($order_statuses as $status) { ?>
        <option value="<?= $status['name'] ?>"><?= $status['name'] ?></option>
      <?php } ?>
    </select>
    <button type="submit" name="update">Submit</button>
  </form>
</div>

</div>


</div>


</div>
</div>
</form>
            </div>
         </div>
      </div>
   </div>
</div>



<?php include_once('../includes/footer.php'); ?>
<!-- <form method="POST" action="">
                                    
                             
 <div class="card-body--">
                 <div class="table-stats order-table ov-h">
       <table>
       <thead>
           <tr>
               <th>Product Name</th>
               <th>Product Image</th>
               <th>Quantity</th>
               <th>price</th>
               <th>Total Price</th>
               
           </tr>
       </thead>
       <tbody>
           <?php foreach($orders as $order){?>
           <tr>
               <td><?= $order['product_name'] ?></td>
               <td><img src="<?= ADMINURL ?>/images/product/<?= $order['product_image'] ?>" alt="<?= $order['product_name'] ?>"></td>
               <td><?= $order['qty'] ?></td>
               <td>$<?= $order['product_price'] ?></td>
               <td>$<?= $order['total'] ?></td>
              
               
           </tr>
           <?php }?>
          
       </tbody>
   </table>
</div>
</div>
</form> -->