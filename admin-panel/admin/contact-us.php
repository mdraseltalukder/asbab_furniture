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


$selectall="SELECT * FROM contact_us ORDER BY id DESC";
$result=$conn->query($selectall);
$contact_us=$result->fetch_all(MYSQLI_ASSOC);





?>

<div class="pb-0 content">
            <div class="orders">
               <div class="row">
                  <div class="col-xl-12">
                     <div class="card">
                        <div class="card-body">
                           <h4 class="box-title">Contact Us </h4>
                        </div>
                        <div class="card-body--">
                           <div class="table-stats order-table ov-h">
                              <table class="table">
                                 <thead>
                                    <tr>
                                       <th class="serial">#</th>
                                       <th class="avatar">name</th>
                                       <th>email</th>
                                       <th>phone</th>

                                       <th>massage</th>
                                       <th>created_at</th>
                                       <th>Delete</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php foreach($contact_us as $contact){?>
                                    <tr>
                                       <td class="serial"><?= $contact['id'] ?></td>
                                       <td class="serial"><?= $contact['name'] ?></td>
                                       <td class="serial"><?= $contact['email'] ?></td>
                                       <td class="serial"><?= $contact['phone'] ?></td>
                                     
                                    
                                       <td class="serial"><?= $contact['comment'] ?>  </td>
                                       <td class="serial"><?= $contact['created_at'] ?>  </td>
                                      
                                       
                                       <td>
                                          <?php 
                                           echo '<a class="bg-danger pt-1 pr-2 pb-1 pl-2 rounded text-white text-whitebg-danger" href="delete.php?&id=' . $contact['id'] . '&created_at=' . $contact['created_at'] . '">Delete</a>';
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