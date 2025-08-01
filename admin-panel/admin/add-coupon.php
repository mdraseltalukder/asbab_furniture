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





 $coupon_code='';
 $coupon_value='';
 $coupon_type='';
 $cart_min_value=0;
 $coupon_status=0;
$btnText="Add Coupon";
// edit category


if(isset($_GET['id']) && $_GET['type'] == 'edit'){
$select="SELECT * FROM coupon WHERE id = $_GET[id]";
$result=$conn->query($select);
if($result->num_rows > 0){
$products=$result->fetch_assoc();

$coupon_code=$products['coupon_code'];
 $coupon_value=$products['coupon_value'];
 $coupon_type=$products['coupon_type'];
 $cart_min_value=$products['cart_min_value'];
 $coupon_status=$products['coupon_status'];
$btnText="Update Coupon";

}
}

if(isset($_POST['add']) && isset($_GET['id']) && $_GET['type'] == 'edit'){



    $id = (int) $_GET['id'];
$coupon_code= trim($_POST['coupon_code']);
  $coupon_value= trim($_POST['coupon_value']);
  $coupon_type= trim($_POST['coupon_type']);
  $cart_min_value= trim($_POST['cart_min_value']);
  $coupon_status= isset($_POST['coupon_status']) ? (int) $_POST['coupon_status'] : 0;







$select="SELECT * FROM coupon WHERE  coupon_code='$coupon_code' AND id != $id ";
$result=$conn->query($select);
if($result->num_rows > 0){
    $_SESSION['error'] = "Coupon Already Exists";
}else{
  $update = "UPDATE `coupon` SET `coupon_code`='$coupon_code',`coupon_value`='$coupon_value',`coupon_type`='$coupon_type',`cart_min_value`='$cart_min_value',`coupon_status`='$coupon_status' WHERE id = $id";
    
    $conn->query($update);
    header("Location: coupon.php");
    exit();
    
}
}


// add product
if(isset($_POST['add']) && !isset($_GET['id']) && !isset($_GET['type'])){
$coupon_code= trim($_POST['coupon_code']);
  $coupon_value= trim($_POST['coupon_value']);
  $coupon_type= trim($_POST['coupon_type']);
  $cart_min_value= trim($_POST['cart_min_value']);
  $coupon_status= isset($_POST['coupon_status']) ? (int) $_POST['coupon_status'] : 0;


    $selectall="SELECT * FROM coupon WHERE coupon_code = '$coupon_code'";
    $result=$conn->query($selectall);

    if($result->num_rows == 0){
    $categories=$result->fetch_all(MYSQLI_ASSOC);
    
    $insert = "INSERT INTO `coupon`( `coupon_code`, `coupon_value`, `coupon_type`, `cart_min_value`, `coupon_status`) VALUES ('$coupon_code','$coupon_value','$coupon_type','$cart_min_value','$coupon_status')";
    $conn->query($insert);
    header("Location: coupon.php");
    exit();
    }else{
        $_SESSION['error'] = "coupon Already Exists";
    }
}





?>

<div class="pb-0 content">
            <div class="orders">
               <div class="row">
                  <div class="col-xl-12">
                     <div class="card">
                        <div class="card-body">
                           <h4 class="box-title">Add Coupon </h4>

                           <?php 
                           if(isset($_SESSION['error'])){
                               echo "<div class='alert alert-danger'>{$_SESSION['error']}</div>";
                               unset($_SESSION['error']);
                           }
                           if(isset($_SESSION['success'])){
                               echo "<div class='alert alert-success'>{$_SESSION['success']}</div>";
                               unset($_SESSION['success']);
                           }
                           
                           ?>
                           
                        </div>
                        <div class="card-body--">
                           <div class="table-stats order-table ov-h">
                            <form action="" method="POST" enctype="multipart/form-data" class="col-lg-12">
   

  
  <div class="form-group">
    
  <label for="company" class="form-control-label">Coupon Code</label>
  
  <input type="text" id="company" placeholder="Enter your Coupon Code" class="form-control" name="coupon_code" value="<?= htmlspecialchars($coupon_code ?? '') ?>" required>

</div>

  <div class="form-group">
  <label for="company" class="form-control-label">Coupon Value</label>
  <input type="text" id="company" placeholder="Enter your Coupon Value" class="form-control" name="coupon_value" value="<?= htmlspecialchars($coupon_value ?? '') ?>" required>
    
  </div>

  <div class="form-group">
  <label for="company" class="form-control-label">Coupon Type</label>
  <select name="coupon_type" class="form-control" id="">
      <?php if($coupon_type=='%'){?>
        <option value="%" selected>%</option>
        <option value="$">$</option>
      <?php }else{?>
        <option value="%">%</option>
        <option value="$" selected>$</option>
      <?php }?>
    </select>
  </div>
  <div class="form-group">
  <label for="company" class="form-control-label">Cart Min Value</label>
  <input type="text" id="company" placeholder="Enter your Cart Min Value" class="form-control" name="cart_min_value" value="<?= htmlspecialchars($cart_min_value ?? '') ?>" required>
  </div>
  <div class="form-group">
    <label for="company" class="form-control-label">Coupon Status</label>
    <select name="coupon_status" class="form-control" id="">
      <?php if($coupon_status==1){?>
        <option value="1" selected>Active</option>
        <option value="0">Inactive</option>
      <?php }else{?>
        <option value="1">Active</option>
        <option value="0" selected>Inactive</option>
      <?php }?>
    </select>






   <button id="" type="submit" class="btn-block btn btn-lg btn-info" name="add">
                           <span id="payment-button-amount"><?= $btnText ?></span>
                           </button>

                            </form>
                            
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
		  </div>

<?php 
include_once('../includes/footer.php');
?>