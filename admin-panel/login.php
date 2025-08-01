<?php 
session_start();
include_once('../connection/conn.php');

if(isset($_SESSION['admin'])){
   header("Location: index.php");
   exit();
}


if(isset($_POST['login'])){

   $name = trim($_POST['name']);
   $password = $_POST['password'];

   if(empty($name) || empty($password)) {
      $_SESSION['error'] = "All fields are required";
   } else {
      $select = "SELECT * FROM admin_users WHERE username = '$name'";
      $result = $conn->query($select);

      if($result->num_rows > 0){
         $user = $result->fetch_assoc();

         if(password_verify($password, $user['password'])){
            $_SESSION['admin'] = $user;
            header("Location:admin/product.php");
            exit();
         } else {
            $_SESSION['error'] = "Invalid login credentials";
         }

      } else {
         $_SESSION['error'] = "Invalid login credentials";
      }
   }
}
?>


<!doctype html>
<html class="no-js" lang="">
   <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Login Page</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="assets/css/normalize.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/font-awesome.min.css">
      <link rel="stylesheet" href="assets/css/themify-icons.css">
      <link rel="stylesheet" href="assets/css/pe-icon-7-filled.css">
      <link rel="stylesheet" href="assets/css/flag-icon.min.css">
      <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
      <link rel="stylesheet" href="assets/css/style.css">
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
   </head>
   <body class="bg-dark">
      <div class="d-flex flex-wrap align-content-center sufee-login">
         <div class="container">
            <div class="login-content">
               <div class="mt-150 login-form">
                  <form method="POST">
                     <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" placeholder="username" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                     </div>
                     <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Password" name="password">
                     </div>

                     <?php 
                     if(isset($_SESSION['error'])){
                        echo "<div class='alert alert-danger'>".$_SESSION['error']."</div>";
                        unset($_SESSION['error']);
                     }
                     ?>

                     <button type="submit" class="m-b-30 m-t-30 btn btn-success btn-flat" name="login">Sign in</button>
                  </form>
               </div>
            </div>
         </div>
      </div>

      <script src="assets/js/vendor/jquery-2.1.4.min.js" type="text/javascript"></script>
      <script src="assets/js/popper.min.js" type="text/javascript"></script>
      <script src="assets/js/plugins.js" type="text/javascript"></script>
      <script src="assets/js/main.js" type="text/javascript"></script>
   </body>
</html>
