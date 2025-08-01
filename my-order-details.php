 <?php include_once('includes/header.php');
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
    orders.user_id = $user_id AND order_details.order_id = $order_id
ORDER BY 
    order_details.id DESC
";



$result = $conn->query($select);
$orders = $result->fetch_all(MYSQLI_ASSOC);

 $subtotal = 0; // Initialize subtotal variable
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
                <th>Product Name</th>
                <th>Product Image</th>
                <th>Quantity</th>
                <th>price</th>
                <th>Total Price</th>

                
            </tr>
        </thead>
        <tbody>
            <?php foreach($orders as $order){
                $subtotal = $order['order_total_price'] ;
             
                ?>
            <tr>
                <td><?= $order['product_name'] ?></td>
                <td><img src="<?= APPURL ?>/admin-panel/images/product/<?= $order['product_image'] ?>" alt="<?= $order['product_name'] ?>"></td>
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





</div>
</form>

        <?php include_once('includes/footer.php');?>