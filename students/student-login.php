<?php
  require_once '../core/init.php';
  if (isLoggedInStudent()) {
   Redirect::to('student-dashboard');
  }
  if (verifyCheck()) {
    Session::flash('emailVerify', 'Please verify your email address!', 'warning');
    Redirect::to('student-verify');
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
        <div class="col-lg-3 col-md-12">
          <?php if (Session::exists('warning')): ?>
             <div class="alert alert-danger alert-dismissible">
                 <button type="button" class="close" data-dismiss="alert">
                &times;
                </button>
         <i class="fa fa-warning"></i>&nbsp;
                 <strong class="text-left">
                     <?=Session::flash('warning') ?>
                 </strong>
             </div>
         <?php endif ?>
       </div>
      <div class="col-lg-6 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-warning">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <span class="nav-tabs-title">Form:</span>
                <ul class="nav nav-tabs" data-tabs="tabs">
                  <li class="nav-item">
                    <a class="nav-link active" href="#" data-toggle="tab">
                      <i class="material-icons fa fa-sign-in fa-lg"></i> Login Student
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
                <hr>
                <form class="form" action="#" method="post" enctype="multipart/form-data" id="loginStudentForm">
                  <div class="form-group">
                    <label for="stud_regNo">Matric No: <sup class="text-danger">*</sup></label>
                      <input type="text" name="stud_regNo" id="stud_regNo" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="password">Password: <sup class="text-danger
                      ">*</sup></label>
                      <input type="password" name="password" id="password" class="form-control">
                  </div>
                  <div class="form-group">
                    <button type="button" name="login" id="loginBtn" class="btn btn-info btn-block">Login</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-12" id="showMessage">

      </div>

    </div>
  </div>
</div>






<?php
  require APPROOT . '/includes/footer.php';
 ?>
 <script type="text/javascript">
   $(document).ready(function(){
     // admin login
     $('#loginBtn').click(function(e){
       e.preventDefault();
       $.ajax({
         url:'script/login-process.php',
         method:'post',
         data:$('#loginStudentForm').serialize()+'&action=loginStudent',
         beforeSend:function(){
           $('#loginBtn').html('<img src="../gif/tra.gif">Checking...');
         },
         success:function(response){
           if (response==='success') {
               $('#loginStudentForm')[0].reset();
               $('#showMessage').html('<div id="" class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert"> &times;</button><i class="fa fa-check"></i>&nbsp; <span><img src="../gif/trans2.gif"> Redirecting.....!</span></div>');
               setTimeout(function(){
                   window.location = "student-dashboard";
               },3000);

           }else{
             $('#showMessage').html(response);
             // setTimeout(function(){
             //     $('#showError').html('');
             // },10000);

           }
         },
         complete:function(){
           $('#loginBtn').html('Done');
         }
       })
     });



     // setInterval(function(){
     //   fetchAdminData();
     // },1000)
   })
 </script>
