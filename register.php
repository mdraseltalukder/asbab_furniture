<?php 
include_once('includes/header.php');

if(isset($_SESSION['user'])){
   header("Location: index.php");
   exit();
}

if(isset($_POST['register'])){
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = trim($_POST['password']);

    // Validation
    if(empty($name)){
        $_SESSION['error'] = "Name is required";
    } elseif(empty($email)){
        $_SESSION['error'] = "Email is required";
    } elseif(empty($phone)){
        $_SESSION['error'] = "Phone is required";
    } elseif(empty($password)){
        $_SESSION['error'] = "Password is required";
    } else {
        // Password hashing
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $select = "SELECT * FROM users WHERE email='$email'";
        $run = $conn->query($select);

        if($run && $run->num_rows == 0){
            // ✅ Email doesn't exist, proceed to insert
            $insert = "INSERT INTO users (name, email, password, phone) VALUES ('$name', '$email', '$hashedPassword', '$phone')";
            if($conn->query($insert)){
                $name='';
                $email='';
                $phone='';
                $password='';
                 ?>
             <script>
             window.location.href = "login.php";
             </script>

              <?php
exit();

            } else {
                $_SESSION['error'] = "Something went wrong while registering.";
            }
        } else {
            $_SESSION['error'] = "Email already exists";
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
                
				

					<div class="col-md-12">
						<div class="mt--60 contact-form-wrap">
							<div class="col-xs-12">
								<div class="contact-title">
									<h2 class="title__line--6">Register</h2>
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
								<form id="contact-form"  method="POST">
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="text" name="name" placeholder="Your Name*" style="width:100%" value="<?= htmlspecialchars($name ??'') ?>">
										</div>
									</div>
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="email" name="email" placeholder="Your Email*" style="width:100%" value="<?= htmlspecialchars($email ??'') ?>">
										</div>
									</div>
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="text" name="phone" placeholder="Your Mobile*" style="width:100%" value="<?= htmlspecialchars($phone ??'') ?>">
										</div>
									</div>
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="password" name="password" placeholder="Your Password*" style="width:100%" value="<?= htmlspecialchars($password ??'') ?>">
										</div>
									</div>
									
									<div class="contact-btn">
										<button type="submit" name="register" class="fv-btn">Register</button>
									</div>
								</form>
								<div class="form-output">
									<p class="form-messege"></p>
								</div>
							</div>
						</div> 
                        <p >Already Have an Account <a href="<?= APPURL ?>/login.php" class="text-info">Login</a> Now</p>
				
                
				</div>
					
            </div>
        </section>
        <!-- End Contact Area -->
        <!-- End Banner Area -->
        <!-- Start Footer Area -->
       <?php include_once('includes/footer.php');?>