<?php
  require_once '../core/init.php';
  if (!isLoggedInStudent()) {
      Session::flash('warning', 'You must login to access that page');
     Redirect::to('student-login');
    }

  require APPROOT . '/includes/head.php';
 ?>
<style media="screen">
  .form-control{
    color: #fff;
  }
</style>


<div class="content">
  <div class="container-fluid">
    <div class="row mt-5">
      <!-- table -->
        <div class="col-lg-3 col-md-12" id="showError">

        </div>
      <div class="col-lg-6 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-warning">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <span class="nav-tabs-title">Form:</span>
                <ul class="nav nav-tabs" data-tabs="tabs">
                  <li class="nav-item">
                    <a class="nav-link active" href="#profile" data-toggle="tab">
                      <i class="material-icons fa fa-sign-in fa-lg"></i> Device Verification
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane active">
                <span class="text-info"> Please enter the token sent to your email to verify your device</span>
                <hr>
                <form class="form" action="#" method="post" id="verifyTokenform">
                  <div class="form-group">
                    <label for="secure_token">Token: <sup class="text-danger">*</sup></label>
                      <input type="number" name="secure_token" id="secure_token" class="form-control">
                  </div>
                  <div class="form-group">
                    <button type="button"  id="verifyBtn" class="btn btn-info">Verify</button>
                    <button type="button"  id="resendBtn" class="btn btn-danger  float-right">Resend</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-12"></div>

    </div>
  </div>
</div>






<?php
  require APPROOT . '/includes/footer.php';
 ?>
 <script type="text/javascript">
   $(document).ready(function(){
 // verify token
     $('#verifyBtn').click(function(e){
       e.preventDefault();
       $.ajax({
         url:'script/otp-process.php',
         method:'post',
         data: $('#verifyTokenform').serialize()+'&action=verifyDevice',
         beforeSend:function(){
           $('#verifyBtn').html('<img src="../gif/tra.gif">Checking...');
         },
         success:function(response){
           if (response==='success') {
             $('#verifyTokenform')[0].reset();
             window.location = 'student-dashboard'
           }else{
             $('#showError').html(response);
           }

         }
       })
     })


 // resend token

         $('#resendBtn').click(function(e){
           e.preventDefault();
           action = 'resendOTPdevice';
           $.ajax({
             url:'script/otp-process.php',
             method:'post',
             data: {action: action},
             beforeSend:function(){
               $('#resendBtn').html('<img src="../gif/tra.gif">&nbsp;Resending...');
             },
             success:function(response){
               if (response==='success') {
                 $('#showError').html('<div id="" class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert"> &times;</button><i class="fa fa-check"></i>&nbsp; <span>Token Resent Successfully!</span></div>');

               }else{
                 $('#showError').html(response);
               }

             },
             complete:function(){
               $('#resendBtn').html('Done');
             },
           })
         })
   })
 </script>
