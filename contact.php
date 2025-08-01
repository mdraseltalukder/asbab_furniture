<?php include_once('includes/header.php'); ?>
        


<?php 



if(isset($_POST['submit'])){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $message = trim($_POST['message']);
    if(empty($name)){
        $_SESSION['error'] = "Name is required";
    }elseif(empty($email)){
        $_SESSION['error'] = "Email is required";
    }elseif(empty($phone)){
        $_SESSION['error'] = "Phone is required";
    }elseif(empty($message)){
        $_SESSION['error'] = "Message is required";
    }else{
        $insert = "INSERT INTO `contact_us`(`name`, `email`, `phone`, `comment`) VALUES ('$name','$email','$phone','$message')";
        $result =$conn->query($insert);
        if($result){
        $_SESSION['success'] = "Your message has been sent successfully";
        }else{
            $_SESSION['error'] = "Something went wrong";
        }
    }
}

?>


<!-- Start Bradcaump area -->
<div class="ht__bradcaump__area" style="background: url(images/bg/4.jpg) no-repeat center center / cover;">
    <div class="ht__bradcaump__wrap">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="bradcaump__inner">
                        <nav class="bradcaump-inner">
                          <a class="breadcrumb-item" href="index.php">Home</a>
                          <span class="brd-separetor"><i class="zmdi-chevron-right zmdi"></i></span>
                          <span class="breadcrumb-item active">Contact Us</span>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Start Contact Area -->
<section class="htc__contact__area ptb--100 bg__white">
    <div class="container">
        <div class="row">
            <!-- Google Map -->
            <div class="col-lg-7 col-md-6 col-sm-12">
                <div class="map-contacts--2">
                    <div id="googleMap" style="width: 100%; height: 400px;"></div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-5 col-md-6 col-sm-12">
                <h2 class="title__line--6">CONTACT US</h2>
                <div class="address">
                    <div class="address__icon">
                        <i class="icon-location-pin icons"></i>
                    </div>
                    <div class="address__details">
                        <h2 class="ct__title">Our Address</h2>
                        <p>666 5th Ave New York, NY, United States</p>
                    </div>
                </div>
                <div class="address">
                    <div class="address__icon">
                        <i class="icon-envelope icons"></i>
                    </div>
                    <div class="address__details">
                        <h2 class="ct__title">Email</h2>
                        <p>support@example.com</p>
                    </div>
                </div>
                <div class="address">
                    <div class="address__icon">
                        <i class="icon-phone icons"></i>
                    </div>
                    <div class="address__details">
                        <h2 class="ct__title">Phone</h2>
                        <p>+123-456-7890</p>
                    </div>
                </div>
            </div>      
        </div>

        <!-- Contact Form -->
        <div class="mt--60 row">
            <div class="col-xs-12">
                <div class="contact-title">
                    <h2 class="title__line--6">SEND A MAIL</h2>
                </div>
                <form id="contact-form" method="POST">
                    <div class="single-contact-form">
                        <div class="contact-box name">
                            <input type="text" name="name" placeholder="Your Name*" value="<?= htmlspecialchars($name ?? "") ?>">
                            <input type="email" name="email" placeholder="Email*" value="<?= htmlspecialchars($email ?? "") ?>">
                        </div>
                    </div>
                    <div class="single-contact-form">
                        <div class="contact-box subject">
                            <input type="number" name="phone" placeholder="phone*" value="<?= htmlspecialchars($phone ?? "") ?>">
                        </div>
                    </div>
                    <div class="single-contact-form">
                        <div class="contact-box message">
                            <textarea name="message" placeholder="Your Message"><?= htmlspecialchars($message ?? "") ?></textarea>
                        </div>
                    </div>
                    <?php 
                    if(isset($_SESSION['error'])){
                        echo "<span class='alert alert-danger'>".$_SESSION['error']."</span>";
                        unset($_SESSION['error']);
                    }
                    if(isset($_SESSION['success'])){
                        echo "<span class='alert alert-success'>".$_SESSION['success']."</span>";
                        unset($_SESSION['success']);
                    }
                    
                    ?>
                    <div class="contact-btn">
                        <button type="submit" name="submit" class="fv-btn">Send MESSAGE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Load Google Map Script -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY"></script>


<script>
    function initMap() {
        var myLatLng = { lat: 23.7286, lng: 90.3854 };

        var map = new google.maps.Map(document.getElementById("googleMap"), {
            zoom: 12,
            center: myLatLng,
            scrollwheel: false,
        });

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: "Your Company Location",
            animation: google.maps.Animation.BOUNCE
        });
    }

    window.onload = initMap;
</script>

<?php include('includes/footer.php'); ?>
