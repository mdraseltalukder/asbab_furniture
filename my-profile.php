   <?php include_once('includes/header.php');
     
if(!isset($_SESSION['user'])){
   header("Location: login.php");
   exit();
}

$name = $_SESSION['user']['name'] ?? '';
$user_id = $_SESSION['user']['id'];

if (isset($_POST['update'])) {
    $name = $_POST['name'] ?? '';

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $name, $user_id); // s = string, i = integer

    if ($stmt->execute()) {
        $_SESSION['user']['name'] = $name;
        $_SESSION['success'] = "Profile updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update profile.";
    }

    $stmt->close(); // Optional but good practice
}


if(isset($_POST['update_password'])) {
    $current_password = $_POST['password'] ?? '';
    $new_password = $_POST['n_pass'] ?? '';
    $confirm_password = $_POST['c_pass'] ?? '';

    // Input validation
    if(empty($current_password)) {
        $_SESSION['errorr'] = "Current password is required.";
    } elseif(empty($new_password)) {
        $_SESSION['errorr'] = "New password is required.";
    } elseif(empty($confirm_password)) {
        $_SESSION['errorr'] = "Confirm password is required.";
    } elseif($new_password !== $confirm_password) {
        $_SESSION['errorr'] = "New password and confirm password do not match.";
    } else {
        // Get current password hash from DB
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Verify current password
        if($user && password_verify($current_password, $user['password'])) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update with prepared statement
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $hashed_password, $user_id);

            if($update_stmt->execute()) {
                $_SESSION['successs'] = "Password updated successfully.";
            } else {
                $_SESSION['errorr'] = "Failed to update password.";
            }

            $update_stmt->close();
        } else {
            $_SESSION['errorr'] = "Current password is incorrect.";
        }

        $stmt->close();
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
                                  <span class="breadcrumb-item active">My Profile</span>
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
									<h2 class="title__line--6">My Profile</h2>
								</div>
								
							</div>
							<div class="col-xs-12 col-md-6">
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
								<form id="contact-form"  method="post">
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="text" name="name"  style="width:100%" value="<?= htmlspecialchars($name ?? "") ?>">
										</div>
									</div>
									
									
									<div class="contact-btn">
										<button type="submit" name="update" class="fv-btn">Update</button>
									</div>
								</form>
								
							</div>
							<div class="col-xs-12 col-md-6">
                                <?php 
                                if(isset($_SESSION['errorr'])){
                                    echo "<div class='alert alert-danger'>".$_SESSION['errorr']."</div>";
                                    unset($_SESSION['errorr']);
                                }
                                if(isset($_SESSION['successs'])){
                                    echo "<div class='alert alert-success'>".$_SESSION['successs']."</div>";
                                    unset($_SESSION['successs']);
                                }
                                ?>
								<form id="contact-form"  method="post">
								
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="password" name="password" placeholder="Enter Your Current Password*" style="width:100%">
										</div>
									</div>
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="password" name="n_pass" placeholder="Enter a new Password" style="width:100%" value="<?= htmlspecialchars($new_password ?? "") ?>">
										</div>
									</div>
									<div class="single-contact-form">
										<div class="contact-box name">
											<input type="password" name="c_pass" placeholder="Enter Confirm Password" style="width:100%" value="<?= htmlspecialchars($confirm_password ?? "") ?>">
										</div>
									</div>
									
									<div class="contact-btn">
										<button type="submit" name="update_password" class="fv-btn">Update_password</button>
									</div>
								</form>
								
							</div>
						</div> 
                
				</div>
				
				

				
					
            </div>
        </section>
        <!-- End Contact Area -->
        <!-- End Banner Area -->
        <!-- Start Footer Area -->
       <?php include_once('includes/footer.php');?>