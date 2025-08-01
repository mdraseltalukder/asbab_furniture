<?php include_once('includes/header.php');?>
      <div id="right-panel" class="right-panel">
         <header id="header" class="header">
            <div class="top-left">
               <div class="navbar-header">
                  <a class="navbar-brand" href="index.php"><img src="images/logo.png" alt="Logo"></a>
                  <a class="hidden navbar-brand" href="index.php"><img src="images/logo2.png" alt="Logo"></a>
                  <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
               </div>
            </div>
            <div class="top-right">
               <div class="header-menu">
                  <div class="float-right user-area dropdown">
                     <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Welcome Admin</a>
                     <div class="user-menu dropdown-menu">
                        <a class="nav-link" href="#"><i class="fa fa-power-off"></i>Logout</a>
                     </div>
                  </div>
               </div>
            </div>
         </header>
         <div class="pb-0 content">
            <div class="animated fadeIn">
               <div class="row">
                  <div class="col-lg-12">
                     <div class="card">
                        <div class="card-header"><strong>Company</strong><small> Form</small></div>
                        <div class="card-block card-body">
                           <div class="form-group"><label for="company" class="form-control-label">Company</label><input type="text" id="company" placeholder="Enter your company name" class="form-control"></div>
                           <div class="form-group"><label for="vat" class="form-control-label">VAT</label><input type="text" id="vat" placeholder="DE1234567890" class="form-control"></div>
                           <div class="form-group"><label for="street" class="form-control-label">Street</label><input type="text" id="street" placeholder="Enter street name" class="form-control"></div>
                           <div class="form-group"><label for="country" class="form-control-label">Country</label><input type="text" id="country" placeholder="Country name" class="form-control"></div>
                           <button id="payment-button" type="submit" class="btn-block btn btn-lg btn-info">
                           <span id="payment-button-amount">Submit</span>
                           </button>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
         <footer class="site-footer">
            <div class="bg-white footer-inner">
               <div class="row">
                  <div class="col-sm-6">
                     Copyright &copy; 2018 Ela Admin
                  </div>
                  <div class="text-right col-sm-6">
                     Designed by <a href="https://colorlib.com/">Colorlib</a>
                  </div>
               </div>
            </div>
         </footer>
      </div>
      <script src="assets/js/vendor/jquery-2.1.4.min.js" type="text/javascript"></script>
      <script src="assets/js/popper.min.js" type="text/javascript"></script>
      <script src="assets/js/plugins.js" type="text/javascript"></script>
      <script src="assets/js/main.js" type="text/javascript"></script>
   </body>
</html>