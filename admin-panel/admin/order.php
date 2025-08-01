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


$select= "SELECT * FROM orders ORDER BY id DESC";
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
                <th>Order Id</th>
                <th>Order date</th>
                <th>address</th>
                <th>Total price</th>
                <th>payment type</th>
                <th>payment status</th>
                <th>Order status</th>
                <th> details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($orders as $order){?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= $order['created-at'] ?></td>
                <td><?= $order['address'] ?></td>
                <td>$<?= $order['total_price'] ?></td>
                <td><?= $order['payment_type'] ?></td>
                <td><?= $order['payment_status'] ?></td>
                <td>


                <?PHP 
  
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
  
  ?>
    </td>
                <td><a class="details_btn" href="order-details.php?id=<?= $order['id'] ?>">Details</a></td>
                
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