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





 $name='';
 $email='';
 $password='';
 $mobile='';

$btnText="Add vendor";
// edit category


if(isset($_GET['id']) && $_GET['type'] == 'edit'){
$select="SELECT * FROM admin_users WHERE id = $_GET[id]";
$result=$conn->query($select);
if($result->num_rows > 0){
$products=$result->fetch_assoc();

$name=$products['username'];
 $email=$products['email'];
 $password=$products['password'];
 $mobile=$products['mobile'];
$btnText="Update vendor";

}
}

if (isset($_POST['add']) && isset($_GET['id']) && $_GET['type'] == 'edit') {

    $id = (int) $_GET['id'];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $mobile = trim($_POST['mobile']);
    $pass = password_hash($password, PASSWORD_DEFAULT);

    // Check if user already exists (excluding current ID)
    $select = "SELECT * FROM admin_users WHERE email = '$email' AND username = '$name' AND id != $id";
    $result = $conn->query($select);

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Vendor already exists";
    } else {
        // ✅ Remove comma before WHERE
        $update = "UPDATE admin_users 
                   SET username = '$name', 
                       email = '$email', 
                       password = '$pass', 
                       mobile = '$mobile', 
                       role = 1, 
                       status = 1 
                   WHERE id = $id";

        if ($conn->query($update)) {
            header("Location: vendor_management.php");
            exit();
        } else {
            echo "Update failed: " . $conn->error;
        }
    }
}



// add product
if(isset($_POST['add']) && !isset($_GET['id']) && !isset($_GET['type'])){
 $name= trim($_POST['name']);
  $email= trim($_POST['email']);
  $password= trim($_POST['password']);
  $mobile= trim($_POST['mobile']);
$pass=password_hash($password, PASSWORD_DEFAULT);



    $selectall="SELECT * FROM admin_users WHERE username = '$name'";
    $result=$conn->query($selectall);

    if($result->num_rows == 0){
    $categories=$result->fetch_all(MYSQLI_ASSOC);
    
    $insert = "INSERT INTO `admin_users`( `username`, `email`, `password`, `mobile`,`role`,`status`) VALUES ('$name','$email','$pass','$mobile',1,1)";
    $conn->query($insert);
     header("Location: vendor_management.php");
    exit();
    }else{
        $_SESSION['error'] = "vendor Already Exists";
    }
}





?>

<div class="pb-0 content">
            <div class="orders">
               <div class="row">
                  <div class="col-xl-12">
                     <div class="card">
                        <div class="card-body">
                           <h4 class="box-title">Add Venddor </h4>

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
    
  <label for="company" class="form-control-label">Username</label>
  
  <input type="text" id="company" placeholder="Enter your Coupon Code" class="form-control" name="name" value="<?= htmlspecialchars($name ?? '') ?>" required>

</div>

  <div class="form-group">
  <label for="company" class="form-control-label">Email</label>
  <input type="text" id="company" placeholder="Enter your Coupon Value" class="form-control" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required>
    
  </div>


  <div class="form-group">
  <label for="company" class="form-control-label">Password</label>
  <input type="text" id="company" placeholder="Enter your Cart Min Value" class="form-control" name="password" value="<?= htmlspecialchars($password ?? '') ?>" required>
  </div>
  <div class="form-group">
    <label for="company" class="form-control-label">Mobile</label>
   <input type="text" id="company" placeholder="Enter your Cart Min Value" class="form-control" name="mobile" value="<?= htmlspecialchars($mobile ?? '') ?>" required>
   </div>






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