 <?php include_once('includes/header.php');
 
$select= "SELECT * FROM orders WHERE user_id = {$_SESSION['user']['id']} ORDER BY id DESC";
$result = $conn->query($select);
$orders = $result->fetch_all(MYSQLI_ASSOC);
 


 
 ?>
 
 <div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(images/bg/4.jpg) no-repeat scroll center center / cover ;">
            <div class="ht__bradcaump__wrap">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="bradcaump__inner">
                                <nav class="bradcaump-inner">
                                  <a class="breadcrumb-item" href="index.php">Home</a>
                                  <span class="brd-separetor"><i class="zmdi-chevron-right zmdi"></i></span>
                                  <span class="breadcrumb-item active">My Order</span>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


         <form method="POST" action="">
                                     
                              
<div class="table-content table-responsive">
    <table>
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
                <th>View Pdf</th>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach($orders as $order){
               
                ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= $order['created-at'] ?></td>
                <td><?= $order['address'] ?></td>
                <td>$<?= $order['total_price'] ?></td>
                <td><?= $order['payment_type'] ?></td>
                <td><?= $order['payment_status'] ?></td>
                <td><?= $order['order_status'] ?></td>
                <td><a class="details_btn" href="my-order-details.php?id=<?= $order['id'] ?>">Details</a></td>
                 <td><a href="generate-pdf.php?id=<?= $order['id'] ?>" target="_blank">🔍 View PDF</a>

</td>
                
            </tr>
            <?php }?>
            
           
        </tbody>
    </table>
</div>
</form>

        <?php include_once('includes/footer.php');?>