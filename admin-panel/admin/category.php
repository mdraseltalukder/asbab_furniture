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

$selectall="SELECT * FROM categories";
$result=$conn->query($selectall);
$categories=$result->fetch_all(MYSQLI_ASSOC);

if(isset($_GET['type']) && $_GET['type'] == 'status' && isset($_GET['id'])){
    $id = $_GET['id'];

    // সঠিকভাবে status বের করি
    $select = "SELECT status FROM categories WHERE id = $id";
    $result = $conn->query($select);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_status = $row['status'];

        // Toggle status
        $new_status = ($current_status == 1) ? 0 : 1;

        // Update query
        $update = "UPDATE categories SET status = $new_status WHERE id = $id";
        $conn->query($update);

        // Redirect to prevent refresh toggle again
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}


?>

<div class="pb-0 content">
            <div class="orders">
               <div class="row">
                  <div class="col-xl-12">
                     <div class="card">
                        <div class="card-body">
                           <h4 class="box-title">Categories </h4>
                           <a href="add-category.php" class="float-right badge badge-complete">Add Categories </a>
                        </div>
                        <div class="card-body--">
                           <div class="table-stats order-table ov-h">
                              <table class="table">
                                 <thead>
                                    <tr>
                                       <th class="serial">#</th>
                                       <th class="avatar">categories</th>
                                       <th>Status</th>
                                       <th>Edit</th>

                                       <th>Delete</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php foreach($categories as $category){?>
                                    <tr>
                                       <td class="serial"><?= $category['id'] ?></td>
                                     
                                    
                                       <td> <span class="name"><?= $category['categories'] ?></span> </td>
                                      
                                       <td>
                                          
                                             
                                             <?php
                                          if($category['status']==1){
                                           echo '<span class="badge badge-complete"><a class="text-white" href="?type=status&id=' . $category['id'] . '&category=' . urlencode($category['categories']) . '">Active</a></span>';

                                          }else{
                                           echo '<span class="badge badge-pending"><a class="text-white" href="?type=status&id=' . $category['id'] . '&category=' . urlencode($category['categories']) . '">Inactive</a></span>';
                                          }
                                            
                                           
                                           ?>
                                       
                                       </td>
                                        <td>
                                          <?php 
                                           echo '<a class="bg-success pt-1 pr-2 pb-1 pl-2 rounded text-white" href="add-category.php?type=edit&id=' . $category['id'] . '">Edit</a>';
                                           ?>
                                       </td>
                                       <td>
                                          <?php 
                                           echo '<a class="bg-danger pt-1 pr-2 pb-1 pl-2 rounded text-white text-whitebg-danger" href="delete.php?&id=' . $category['id'] . '">Delete</a>';
                                           ?>
                                       </td>
                                      
                                    </tr>
                                    <?php }?>
                                  
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
		  </div>

          <?php include_once('../includes/footer.php');?>