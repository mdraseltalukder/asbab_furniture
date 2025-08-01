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




$selectall="SELECT * FROM admin_users";
$result=$conn->query($selectall);
$categories=$result->fetch_all(MYSQLI_ASSOC);

if(isset($_GET['type']) && $_GET['type'] == 'status' && isset($_GET['id'])){
    $id = $_GET['id'];

    // সঠিকভাবে status বের করি
    $select = "SELECT status FROM admin_users WHERE id = $id";
    $result = $conn->query($select);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_status = $row['status'];

        // Toggle status
        $new_status = ($current_status == 1) ? 0 : 1;

        // Update query
        $update = "UPDATE admin_users SET status = $new_status WHERE id = $id";
        $conn->query($update);

        // Redirect to prevent refresh toggle again
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

$selectall = "SELECT * FROM admin_users WHERE role = 1";

$result = $conn->query($selectall);
$products = $result->fetch_all(MYSQLI_ASSOC);


?>

<div class="pb-0 content">
   <div class="orders">
      <div class="row">
         <div class="col-xl-12">
            <div class="card">
               <div class="card-body">
                  <h4 class="box-title">Vendor Management</h4>
                  <a href="add_vendor_management.php" class="float-right badge badge-complete">Add Vendor</a>
               </div>
               <div class="card-body--">
                  <div class="table-statas order-table ov-h">
                     <table class="table">
                        <thead>
                           <tr>
                              <th class="serial">#</th>
                              <th>username</th>
                              <th>email</th>
                              <th>password</th>
                              <th>mobile</th>
                            
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
                                 <td class=""><?= htmlspecialchars($product['username']) ?></td>
                                  <td><span class=""><?= ($product['email']) ?></span></td>
                                 <td class=""><?= ($product['password']) ?></td>
                                 
                                
                                 <td><span class="name"><?= ($product['mobile']) ?></span></td>
    <td>
                                          
                                             
                                             <?php
                                          if($product['status']==1){
                                           echo '<span class="badge badge-complete"><a class="text-white" href="?type=status&id=' . $product['id'] . '&category=' . urlencode($product['username']) . '">Active</a></span>';

                                          }else{
                                           echo '<span class="badge badge-pending"><a class="text-white" href="?type=status&id=' . $product['id'] . '&category=' . urlencode($product['username']) . '">Inactive</a></span>';
                                          }
                                            
                                           
                                           ?>
                                       
                                       </td>

                              
                                 <td>
                                    <a class="bg-success pt-1 pr-2 pb-1 pl-2 rounded text-white" href="add_vendor_management.php?type=edit&id=<?= $product['id'] ?>">Edit</a>
                                 </td>
                                 <td>
                                    <a class="bg-danger pt-1 pr-2 pb-1 pl-2 rounded text-white" href="delete.php?id=<?= $product['id'] ?>&username=<?= urlencode($product['username']) ?>">Delete</a>
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
