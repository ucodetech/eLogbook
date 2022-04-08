<?php
  require_once '../core/init.php';
  require APPROOT . '/includes/head.php';
 ?>
<style media="screen">
  .form-control{
    color: #fff;
  }
</style>


  <!-- End Navbar -->
  <div class="content">
    <div class="container-fluid">
      <h3 class="text-center text-light">Welcome E-Log Book System</h3>
      <hr>
      <div class="row text-center">
        <div class="col-lg-6">
          <a href="student-login" class="btn btn-info btn-lg" target="_blank"><i class="fa fa-sign-in fa-lg"></i>&nbsp;Login</a>
        </div>
        
        <div class="col-lg-6">
          <a href="student-register" class="btn btn-success btn-lg" target="_blank"><i class="fa fa-user-plus fa-lg"></i>&nbsp;Register</a>
        </div>
       
      </div>
    </div>
  </div>


<?php
  require APPROOT . '/includes/footer.php';
 ?>