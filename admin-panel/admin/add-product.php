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





 $category="";
 $name="";
 $mrp="";
 $price="";
 $qty="";
 $best_seller=0;
 $sort_desc="";
 $desc="";
 $meta_title="";
 $meta_desc="";
 $meta_key="";
 $image="";
$btnText="Add Product";
 $image_required="required";
// edit category


if(isset($_GET['id']) && $_GET['type'] == 'edit'){
$select="SELECT * FROM product WHERE id = $_GET[id] $condition2";
$result=$conn->query($select);
if($result->num_rows > 0){
$products=$result->fetch_assoc();

$category=$products['categories_id'];
$name=$products['name'];
$mrp=$products['mrp'];
$price=$products['price'];
$qty=$products['qty'];
$best_seller=$products['best_seller'];
$sort_desc=$products['sort_desc'];
$desc=$products['desc'];
$meta_title=$products['meta_title'];
$meta_desc=$products['meta_desc'];
$meta_key=$products['meta_keyword'];
$image=$products['image'];
$btnText="Update Product";
 $image_required='';

}
}

if(isset($_POST['add']) && isset($_GET['id']) && $_GET['type'] == 'edit'){



    $id = (int) $_GET['id'];
    $category = trim($_POST['category']);
    $name = trim($_POST['name']);
    $mrp = trim($_POST['mrp']);
    $price = trim($_POST['price']);
    $qty= trim($_POST['qty']);
    $best_seller = isset($_POST['best_seller']) ? (int) $_POST['best_seller'] : 0;

    $sort_desc= trim($_POST['sort_desc']);
    $desc = trim($_POST['desc']);
    $meta_title= trim($_POST['meta_title']);
    $meta_desc = trim($_POST['meta_desc']);
    $meta_key = trim($_POST['meta_key']);
$image =rand(1111,9999) . '_' .$_FILES['image']['name'];
    $temp_name = $_FILES['image']['tmp_name'];


    move_uploaded_file($temp_name,"../images/product/$image");






$select="SELECT * FROM product WHERE categories_id = '$category' AND name='$name' AND id != $id $condition2";
$result=$conn->query($select);
if($result->num_rows > 0){
    $_SESSION['error'] = "Name Already Exists";
}else{
    // if image update na kori dei tahole ager image e dekhabe 

if($_FILES['image']['name'] != '') {
    $update = "UPDATE product SET categories_id = '$category', name = '$name', mrp='$mrp', price='$price', qty='$qty', sort_desc='$sort_desc', `desc`='$desc', meta_title='$meta_title', meta_desc='$meta_desc', meta_keyword='$meta_key', image='$image', added_by=$admin_id WHERE id = $id";
}else{
    $update = "UPDATE product SET categories_id = '$category', name = '$name', mrp='$mrp', price='$price', qty='$qty',best_seller='$best_seller', sort_desc='$sort_desc', `desc`='$desc', meta_title='$meta_title', meta_desc='$meta_desc', meta_keyword='$meta_key', added_by=$admin_id  WHERE id = $id";
}
    
    $conn->query($update);
    header("Location: product.php");
    exit();
    
}
}


// add product
if(isset($_POST['add']) && !isset($_GET['id']) && !isset($_GET['type'])){
    $category = trim($_POST['category']);
    $name = trim($_POST['name']);
    $mrp = trim($_POST['mrp']);
    $price = trim($_POST['price']);
    $qty= trim($_POST['qty']);
$best_seller = isset($_POST['best_seller']) ? (int) $_POST['best_seller'] : 0;

    $sort_desc= trim($_POST['sort_desc']);
    $desc = trim($_POST['desc']);
    $meta_title= trim($_POST['meta_title']);
    $meta_desc = trim($_POST['meta_desc']);
    $meta_key = trim($_POST['meta_key']);
    $image =rand(1111,9999) . '_' .$_FILES['image']['name'];
    $temp_name = $_FILES['image']['tmp_name'];


    move_uploaded_file($temp_name,"../images/product/$image");
   
 $image_required='required';


    $selectall="SELECT * FROM product WHERE name = '$name'";
    $result=$conn->query($selectall);

    if($result->num_rows == 0){
    $categories=$result->fetch_all(MYSQLI_ASSOC);
    
    $insert = "INSERT INTO `product`( `categories_id`, `name`, `mrp`, `price`, `qty`,`best_seller`, `sort_desc`, `desc`, `meta_title`, `meta_desc`, `meta_keyword`, `image`,`added_by`) VALUES ('$category','$name','$mrp','$price','$qty','$best_seller','$sort_desc','$desc','$meta_title','$meta_desc','$meta_key','$image',$admin_id)";
    $conn->query($insert);
    header("Location: product.php");
    exit();
    }else{
        $_SESSION['error'] = "product Already Exists";
    }
}





?>

<div class="pb-0 content">
            <div class="orders">
               <div class="row">
                  <div class="col-xl-12">
                     <div class="card">
                        <div class="card-body">
                           <h4 class="box-title">Add product </h4>

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
        <label for="category">Category</label>
  <select name="category" class="form-control" id="category">
 <?php 
$select="SELECT * FROM categories";
$result=$conn->query($select);
$categories=$result->fetch_all(MYSQLI_ASSOC);
foreach($categories as $cat){
    $selected = ($cat['id'] == $category) ? 'selected' : '';
    echo "<option value='{$cat['id']}' $selected>{$cat['categories']}</option>";
}
?>
</select>
</div>

  
  <div class="form-group">
    
  <label for="company" class="form-control-label">Product name</label>
  
  <input type="text" id="company" placeholder="Enter your product name" class="form-control" name="name" value="<?= htmlspecialchars($name ?? '') ?>" required>

</div>
  <div class="form-group">
    
  <label for="company" class="form-control-label">mrp</label>
  
  <input type="text" id="company" placeholder="Enter your mrp" class="form-control" name="mrp" value="<?= htmlspecialchars($mrp ?? '') ?>" required>

</div>
  <div class="form-group">
    
  <label for="company" class="form-control-label">price</label>
  
  <input type="text" id="company" placeholder="Enter your price" class="form-control" name="price" value="<?= htmlspecialchars($price ?? '') ?>" required>

</div>
  <div class="form-group">
    
  <label for="company" class="form-control-label">qty</label>
  
  <input type="text" id="company" placeholder="Enter your qty" class="form-control" name="qty" value="<?= htmlspecialchars($qty ?? '') ?>" required>

</div>
  <div class="form-group">
    
  <label for="company" class="form-control-label">Best Seller</label>
 <select name="best_seller" class="form-control" id="">
  <?php if($best_seller==1){?>
  <option value="1" selected>Yes</option>
    <option value="0" >No</option>
  <?php }else{?>
      <option value="1" >Yes</option>
  <option value="0" selected>No</option>
  <?php }?>
 </select>

</div>

  <div class="form-group">
    
  <label for="company" class="form-control-label">Image</label>
  
  <input type="file" id="company" placeholder="Enter your image" class="form-control" name="image" value="<?= htmlspecialchars($image ?? '') ?>" <?= $image_required ?> >

</div>
  <div class="form-group">
    
  <label for="company" class="form-control-label">sort description</label>
  
  <input type="text" id="company" placeholder="Enter your sort description" class="form-control" name="sort_desc" value="<?= htmlspecialchars($sort_desc ?? '') ?>" required>

</div>
  <div class="form-group">
    
  <label for="company" class="form-control-label">description</label>
  
  <input type="text" id="company" placeholder="Enter your description" class="form-control" name="desc" value="<?= htmlspecialchars($desc ?? '') ?>" required>

</div>
  <div class="form-group">
    
  <label for="company" class="form-control-label">meta title</label>
  
  <input type="text" id="company" placeholder="Enter your meta title" class="form-control" name="meta_title" value="<?= htmlspecialchars($meta_title ?? '') ?>" required>

</div>
  <div class="form-group">
    
  <label for="company" class="form-control-label">meta description</label>
  
  <input type="text" id="company" placeholder="Enter your meta description" class="form-control" name="meta_desc" value="<?= htmlspecialchars($meta_desc ?? '') ?>" required>

</div>
  <div class="form-group">
    
  <label for="company" class="form-control-label">meta keyword</label>
  
  <input type="text" id="company" placeholder="Enter your keyword " class="form-control" name="meta_key" value="<?= htmlspecialchars($meta_key ?? '') ?>" required>

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