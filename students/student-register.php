<?php
  require_once '../core/init.php';
  require APPROOT . '/includes/head.php';

  $general = new General();
  $department = $general->getDepartment();
  $school = $general->getSchool();

 ?>
<style media="screen">
  .form-control{
    color: #029eb1;
  }
</style>


<div class="content">
  <div class="container-fluid">
    <div class="row mt-5">
      <!-- table -->
        <div class="col-lg-2 col-md-12"></div>
      <div class="col-lg-8 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-warning">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <span class="nav-tabs-title">Form:</span>
                <ul class="nav nav-tabs" data-tabs="tabs">
                  <li class="nav-item">
                    <a class="nav-link active" href="#" data-toggle="tab">
                      <i class="material-icons fa fa-sign-in fa-lg"></i> Register
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
                <form class="form" action="#" method="post" enctype="multipart/form-data" id="registerStudentForm">
                  <!-- personal details -->
                  <div class="row p-4">
                  <div class="col-lg-4 form-group">
                    <label for="stud_fname">First Name: <sup class="text-danger">*</sup></label>
                      <input type="text" name="stud_fname" id="stud_fname" class="form-control">
                  </div>
                  <div class="col-lg-4 form-group">
                    <label for="stud_lname">Last Name: <sup class="text-danger">*</sup></label>
                      <input type="text" name="stud_lname" id="stud_lname" class="form-control">
                  </div>
                  <div class="col-lg-4 form-group">
                    <label for="stud_oname">Other Name:</label>
                      <input type="text" name="stud_oname" id="stud_oname" class="form-control">
                  </div>
                   <div class="col-lg-4 form-group">
                    <label for="stud_email">Email: <sup class="text-danger">*</sup></label>
                      <input type="email" name="stud_email" id="stud_email" class="form-control">
                  </div>
                  <div class="col-lg-4 form-group">
                    <label for="stud_tel">Phone No: <sup class="text-danger">*</sup></label>
                      <input type="tel" name="stud_tel" id="stud_tel" class="form-control">
                  </div>
                </div>
                  <!-- school detail -->
                  <div class="row p-4">
                    <div class="form-group col-lg-4">
                     <label for="stud_school">School<sup class="text-danger">*</sup></label>
                    <select name="stud_school" id="stud_school" class="form-control text-info">
                    
                       <option value="" <?= (($school == ''))? ' selected' : '' ;?>>Select School</option>
                     <?php foreach ($school as $sch): ?>

                        <option value="<?=$sch->school;?>" <?= (($school == $sch->school))? ' selected' : '' ;?>><?=$sch->school ?></option>
                     <?php endforeach; ?>



                  </select>
                  </div>
                  <div class="form-group col-lg-4">
                  <label for="stud_department">Department<sup class="text-danger">*</sup></label>
                  <select name="stud_department" id="stud_department" class="form-control text-info">
                    
                       <option value="" <?= (($department == ''))? ' selected' : '' ;?>>Select department</option>
                     <?php foreach ($department as $dpt): ?>

                        <option value="<?=$dpt->department_name;?>" <?= (($department == $dpt->department_name))? ' selected' : '' ;?>><?=$dpt->department_name ?></option>
                     <?php endforeach; ?>



                  </select>
                </div>
                 <div class="col-lg-4 form-group">
                    <label for="stud_level">Level: <sup class="text-danger">*</sup></label>
                     <input type="text" name="stud_level" id="stud_level"  class="form-control">
                  </div>
                 </div>
                  
                 <!-- login details -->
                 <div class="row p-4">
                  <div class="col-lg-4 form-group">
                    <label for="stud_regNo">Matric Number: <sup class="text-danger">*</sup></label>
                      <input type="text" name="stud_regNo" id="stud_regNo" class="form-control">
                  </div>
                  <div class="col-lg-4 form-group">
                    <label for="password">Password: <sup class="text-danger
                      ">*</sup></label>
                      <input type="password" name="password" id="password" class="form-control">
                  </div>
                   <div class="col-lg-4 form-group">
                    <label for="cpassword">Comfirm Password: <sup class="text-danger
                      ">*</sup></label>
                      <input type="password" name="cpassword" id="cpassword" class="form-control">
                  </div>
                  
                 </div>
                 <div class="row">
                   <div class="col-lg-4 form-group">
                    <button type="button" name="register" id="registerBtn" class="btn btn-info btn-block">Register</button>
                  </div>
                   <div class="col-lg-4 form-group">
                    <a href="" class="float-right">Already have account? Login</a>
                  </div>
                 </div>
                 <div class="clearfix"></div>
                 <span id="showMessage"></span>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-2 col-md-12">

      </div>

    </div>
  </div>
</div>






<?php
  require APPROOT . '/includes/footer.php';
 ?>

<script>
   $(document).ready(function(){
      var gifPath = '../gif/tra.gif';
    //register process
    
    $('#registerBtn').click(function(e){
      e.preventDefault();

      $.ajax({
        url:'script/register-process.php',
        method:'post',
        data:$('#registerStudentForm').serialize()+'&action=register',
        beforeSend:function(){
          $('#registerBtn').html('<img src="'+gifPath+'" alt="gif">a moment...');
        },
        success:function(response){
          console.log(response);
          if ($.trim(response)==='success') {
            $('#showMessage').html('<div id="" class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert"> &times; </button><i class="fa fa-check"></i>&nbsp;<span>Success! Redirecting...</span></div>');

            setTimeout(function(){
              window.location = 'student-verify';
            }, 3000);
            
          }else{
            $('#showMessage').html(response);
          
          }
        },
        complete:function(){
          $('#registerBtn').html('Register');
        }
      });
    });

});
</script>