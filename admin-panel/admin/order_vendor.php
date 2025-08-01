 <?php 
 include_once('../includes/header.php');
include_once('../../connection/conn.php');

if(!isset($_SESSION['admin'])){
   header("Location: login.php");
   exit();
}
$admin_id = $_SESSION['admin']['id'] ?? 0;
$role = $_SESSION['admin']['role'] ?? 0;



 
$select= "SELECT 
    orders.*, 
    product.name AS product_name, 
    product.added_by AS product_added_by,
    product.image AS product_image, 
    product.id AS product_id,
    product.categories_id AS categories_id,
    order_details.qty, order_details.total 
FROM 
    orders
JOIN 
    order_details ON order_details.order_id = orders.id
JOIN 
    product ON product.id = order_details.product_id
WHERE 
    product.added_by = $admin_id 
    AND $role = 1
ORDER BY 
    orders.id DESC;
";
$result = $conn->query($select);
$orders = $result->fetch_all(MYSQLI_ASSOC);
 





 
 ?>
 


<div class="pb-0 content">
   <div class="orders">
      <div class="row">
         <div class="col-xl-12">
            <div class="card">
               <div class="card-body">
                  <h4 class="box-title">Order </h4>
               
 <form method="POST" action="">
                                     
                              
  <div class="card-body--">
                  <div class="table-stats order-table ov-h">
                 <table class="table">
        <thead>
           <tr>
               <th>Product Name</th>
               <th>Product Image</th>
               <th>Quantity</th>
               <th>Payment type</th>
               <th>Payment status</th>
               <th>Order status</th>
               
           </tr>
       </thead>
        <tbody>
           <?php foreach($orders as $order){
     
         
            ?>
           <tr>
               <td><?= $order['product_name'] ?></td>
               <td><img src="<?= ADMINURL ?>/images/product/<?= $order['product_image'] ?>" alt="<?= $order['product_name'] ?>"></td>
               <td><?= $order['qty'] ?></td>
               <td><?= $order['payment_type'] ?></td>
               <td><?= $order['payment_status'] ?></td>
               <td>       <?PHP 
  
  if( $order['order_status'] =='Pending') {
    echo '<span class="badge badge-warning">Pending</span>';
  } elseif( $order['order_status'] =='Complete') {
    echo '<span class="badge badge-success">Completed</span>';
  } elseif( $order['order_status'] =='Canceled') {
    echo '<span class="badge badge-danger">Cancelled</span>';
  }elseif( $order['order_status'] =='Processing') {
    echo '<span class="badge badge-info">Processing</span>';
  }else {
    echo '<span class="badge badge-primary">Shipped</span>';
  }
  
  ?></td>
         
              
               
           </tr>
           <?php }?>
           
          
       </tbody>
    </table>
</div>
</div>
</form>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include_once('../includes/footer.php'); ?>