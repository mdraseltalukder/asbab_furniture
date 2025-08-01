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

$category="";
$btnText="Add Category";
// edit category


if(isset($_GET['id']) && $_GET['type'] == 'edit'){
$select="SELECT * FROM categories WHERE id = $_GET[id]";
$result=$conn->query($select);
if($result->num_rows > 0){
$categories=$result->fetch_assoc();
$category=$categories['categories'];
$btnText="Update Category";

}
}

if(isset($_POST['add']) && isset($_GET['id']) && $_GET['type'] == 'edit'){
    $id = (int) $_GET['id'];
    $category = trim($_POST['category']);

$select="SELECT * FROM categories WHERE categories = '$category' AND id != $id ";
$result=$conn->query($select);
if($result->num_rows > 0){
    $_SESSION['error'] = "Category Already Exists";
}else{
    $update = "UPDATE categories SET categories = '$category' WHERE id = $id";
    $conn->query($update);
    header("Location: category.php");
    exit();
    
}
}

// add category
if(isset($_POST['add']) && !isset($_GET['id']) && !isset($_GET['type'])){
    $category = trim($_POST['category']);
    $selectall="SELECT * FROM categories WHERE categories = '$category'";
    $result=$conn->query($selectall);

    if($result->num_rows == 0){
    $categories=$result->fetch_all(MYSQLI_ASSOC);
    
    $insert = "INSERT INTO `categories`( `categories`) VALUES ('$category')";
    $conn->query($insert);
    header("Location: category.php");
    exit();
    }else{
        $_SESSION['error'] = "Category Already Exists";
    }
}





?>

<div class="pb-0 content">
            <div class="orders">
               <div class="row">
                  <div class="col-xl-12">
                     <div class="card">
                        <div class="card-body">
                           <h4 class="box-title">Add Category </h4>

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
                            <form action="" method="POST" class="col-lg-12">
  
  <div class="form-group">
    
  <label for="company" class="form-control-label">Category Name</label>
  
  <input type="text" id="company" placeholder="Enter your category name" class="form-control" name="category" value="<?= htmlspecialchars($category ?? '') ?>">

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