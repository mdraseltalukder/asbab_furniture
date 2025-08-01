   <?php include_once('includes/header.php');
     
if(isset($_SESSION['user'])){
   header("Location: index.php");
   exit();
}



if(isset($_POST['login'])){

   $email = trim($_POST['email']);
   $password = $_POST['password'];

   if(empty($email)){
	  $_SESSION['error'] = "email is required";
   }elseif(empty($password)) {
	$_SESSION['error'] = "Password is required";
}else {
	  $select = "SELECT * FROM users WHERE email = '$email'";
	  $result = $conn->query($select);

	  if($result->num_rows > 0){
		 $user = $result->fetch_assoc();

		 if(password_verify($password, $user['password'])){
			$_SESSION['user'] = $user;
            // Login successful হলে নিচে যুক্ত করো:
if (isset($_SESSION['wishlist_redirect_product'])) {
    $product_id = $_SESSION['wishlist_redirect_product'];
    unset($_SESSION['wishlist_redirect_product']); // clean session

    // JavaScript দিয়ে redirect করা হবে, যাতে AJAX চলে
    echo "
    <script>
        localStorage.setItem('wishlist_add_after_login', $product_id);
        window.location.href = 'index.php'; // বা যেখান থেকে ক্লিক করেছিলে
    </script>";
    exit;
}

			   ?>
             <script>
             window.location.href = "index.php";
             </script>

              <?php
			exit();
		 } else {
			$_SESSION['error'] = "Invalid Password";
		 }
	  } else {
		 $_SESSION['error'] = "Invalid login credentials";
		 }
		 }
		 }




     ?>

       
        <!-- End Offset Wrapper -->
        <!-- Start Bradcaump area -->
        <div class="ht__bradcaump__area" style="background: rgba(0, 0, 0, 0) url(images/bg/4.jpg) no-repeat scroll center center / cover ;">
            <div class="ht__bradcaump__wrap">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="bradcaump__inner">
                                <nav class="bradcaump-inner">
                                  <a class="breadcrumb-item" href="index.php">Home</a>
                                  <span class="brd-separetor"><i class="zmdi-chevron-right zmdi"></i></span>
                                  <span class="breadcrumb-item active">Login</span>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Bradcaump area -->
        <!-- Start Contact Area -->
        <section class="htc__contact__area ptb--100 bg__white">
            <div class="container">
                <div class="row">
					<div class="col-md-12">
						<div class="mt--60 contact-form-wrap">
							<div class="col-xs-12">
								<div class="contact-title">
									<h2 class="title__line--6">Login</h2>
								</div>
								<?php 
                                if(isset($_SESSION['error'])){
                                    echo "<div class='alert alert-danger'>".$_SESSION['error']."</div>";
                                    unset($_SESSION['error']);
                                }
                                if(isset($_SESSION['success'])){
                                    echo "<div class='alert alert-success'>".$_SESSION['success']."</div>";
                                    unset($_SESSION['success']);
                                }
                                ?>
							</div>
							<div class="col-xs-12">
								<form id="contact-form"  method="post">
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="text" name="email" placeholder="Your Email*" style="width:100%" value="<?= htmlspecialchars($email ?? "") ?>">
										</div>
									</div>
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="password" name="password" placeholder="Your Password*" style="width:100%">
										</div>
									</div>
									
									<div class="contact-btn">
										<button type="submit" name="login" class="fv-btn">Login</button>
									</div>
								</form>
								<div class="form-output">
									<p class="form-messege"></p>
								</div>
							</div>
						</div> 
                
				</div>
				<p>Don't Have an Account <a href="<?= APPURL ?>/register.php" class="text-info">Register</a> Now</p>
				

				
					
            </div>
        </section>
        <!-- End Contact Area -->
        <!-- End Banner Area -->
        <!-- Start Footer Area -->
       <?php include_once('includes/footer.php');?>