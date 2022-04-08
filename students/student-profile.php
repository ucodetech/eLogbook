<?php
require_once '../core/init.php';
 if (!isLoggedInStudent()) {
      Session::flash('warning', 'You must login to access that page');
     Redirect::to('student-login');
    }

    $user = new User();
    $uniqueid = $user->data()->stud_unique_id;

  if (verifyCheck()) {
    Session::flash('emailVerify', 'Please verify your email address!', 'warning');
    Redirect::to('student-verify');
  }elseif(isOTPsetUser($uniqueid)){
      Redirect::to('student-otp');
    }

require APPROOT . '/includes/sthead.php';
require APPROOT . '/includes/stnav.php';



?>
<style media="screen">
.signature{
width: 250px !important;
height:250px !important;
}

</style>
<!-- End Navbar -->
<div class="content">
<div class="container-fluid">
<div class="row">
<div class="col-md-8">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-primary">
          <h4 class="card-title">Edit Profile</h4>
          <p class="card-category">Complete your profile</p>
        </div>
        <div class="card-body">
          <form action="#" method="POST" id="updateProfileForm">
              <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">First Name</label>
                  <input type="text" class="form-control" id="stud_fname" name="stud_fname" value="<?=$user->data()->stud_fname;?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Last Name</label>
                  <input type="text" class="form-control" id="stud_lname" name="stud_lname" value="<?=$user->data()->stud_lname?>">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Other Name</label>
                  <input type="text" class="form-control" id="stud_oname" name="stud_oname" value="<?=$user->data()->stud_oname?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Department</label>
                  <input type="text" class="form-control" value="<?=$user->data()->stud_dept?>" disabled>
                </div>
              </div>
               <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Level</label>
                  <input type="text" class="form-control" id="stud_level" name="stud_level" value="<?=$user->data()->stud_level?>" disabled>
                </div>
              </div>
               <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">School</label>
                  <input type="text" class="form-control" id="stud_school" name="stud_school" value="<?=$user->data()->stud_school?>" disabled>
                </div>
              </div>
            </div>
          
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Martric No</label>
                  <input type="text" class="form-control" id="stud_regNo" name="stud_regNo" value="<?=$user->data()->stud_regNo?>" disabled>
                </div>
              </div>
      
              <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Phone No</label>
                  <input type="tel" class="form-control" id="stud_tel" name="stud_tel" value="<?=$user->data()->stud_tel?>">
                </div>
              </div>
               <div class="col-md-4">
                <div class="form-group">
                  <label class="bmd-label-floating">Email address</label>
                  <input type="email" class="form-control" id="stud_email" name="stud_email" value="<?=$user->data()->stud_email?>">
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary pull-right" id="updateProfile">Update Profile</button>
            <div class="clearfix"></div>
            <span id="showError"></span>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-info">
          <h4 class="card-title">Change Password</h4>
          <p class="card-category">Change Your Password</p>
        </div>
        <div class="card-body">
          <form action="#" id="changePasswordForm" method="POST">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Current Password</label>
                  <input type="password" class="form-control" id="stud_password" name="stud_password">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">New Password</label>
                  <input type="password" class="form-control" id="stud_new_password" name="stud_new_password">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="bmd-label-floating">Comfirm New Password</label>
                  <input type="password" class="form-control" id="stud_cnew_password" name="stud_cnew_password">
                </div>
              </div>
            </div>
            <button type="button" class="btn btn-primary pull-right" id="changePassword">Change Password</button>
            <div class="clearfix"></div>
            <span id="errors" class="text-info"> After successful Change of password the system will log you out automatically and for you to re-login</span>
          </form>
        </div>
      </div>
    </div>
  </div>

</div>

<div class="col-md-4">
<div class="row">
  <!-- passport -->
  <div class="col-md-12">
    <div class="card card-profile">
      <div class="card-avatar" id="profileShow">
        <label for="passport_file" style="cursor:pointer;">
          <img class="img" src="profile/<?=$user->data()->passport?>" />
        </label>
      </div>
      <div class="card-body">
        <h4 class="card-title"><?=$user->data()->stud_fname?></h4>
        <p class="card-description">
          <?=$user->data()->stud_email?>
        </p>
        <form action="#" method="post" id="updatePassportForm" enctype="multipart/form-data">

          <div class="form-group">
            <!-- <label for="passport_file" class="bmd-label-floating">Select File</label> -->
          <input type="file" class="form-control" id="passport_file" name="passport_file" style="display:none;">
          </div>

          <button type="submit" class="btn btn-warning btn-round" id="updatePassport">Update</button>
          <div class="clearfix"></div>
          <span id="message"></span>
        </form>

      </div>
    </div>
  </div>
  <!-- signature -->
  <div class="col-md-12">
    <div class="card card-profile">
      <div class="card-avatar" id="signatureShow">
        <label for="signature_file" style="cursor:pointer;">
          <img class="img img-fluid signature" src="signature/<?=$user->data()->signature?>" />
        </label>
      </div>
      <div class="card-body">
        <p class="card-description">
          Update Signature
        </p>
        <form action="#" method="post" id="updateSignatureForm" enctype="multipart/form-data">

          <div class="form-group">
            <!-- <label for="passport_file" class="bmd-label-floating">Select File</label> -->
          <input type="file" class="form-control" id="signature_file" name="signature_file" style="display:none;">
          </div>

          <button type="submit" class="btn btn-info btn-round" id="updateSignature">Update</button>
          <div class="clearfix"></div>
          <span id="messageSignature"></span>
        </form>

      </div>
    </div>
  </div>
</div>
</div>

</div>
</div>
</div>


<?php
require APPROOT . '/includes/stfooter.php';
?>
<script>
function readURL(input){

  if (input.files && input.files[0]) {
     var reader = new FileReader();
    reader.onload = function(e){
      $('#profileShow').html('<img src="'+e.target.result+'" alt="profile pic" class="img">');
    }
    reader.readAsDataURL(input.files[0]);
  }
}

function getURL(input){

    if (input.files && input.files[0]) {
       var reader = new FileReader();
      reader.onload = function(e){
        $('#signatureShow').html('<img src="'+e.target.result+'" alt="signature pic" class="img-fluid signature">');
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

$(document).ready(function(){

$('#updateProfile').click(function(e){
    e.preventDefault();
    $.ajax({
      url: 'script/update-process.php',
      method:'post',
      data: $('#updateProfileForm').serialize()+'&action=update_details',
      beforeSend:function(){
        $('#updateProfile').html('<img src="gif/trans.gif"> Update...');
      },
      success:function(response){
        if(response==='success'){
             $('#showError').html('<div id="" class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert"> &times;</button><i class="fa fa-check"></i>&nbsp; <span>Profile Updated successfully!</span></div>');
        }else{
          $('#showError').html(response);
        }
      },
      complete:function(){
        $('#updateProfile').html('UPDATE PROFILE');
      }
    })
});

//change password
$('#changePassword').click(function(e){
    e.preventDefault();

    $.ajax({
      url:'script/update-process.php',
      method:'post',
      data:$('#changePasswordForm').serialize()+'&action=change_password',
      success:function(response){
        if (response==='changed') {
           $('#changePasswordForm')[0].reset();
           $('#errors').html('<span class="text-info">Your password have been changed! the system will log you out onreload</span>');
           setTimeout(function(){
            location.reload();
           }, 3000);
        }else{
          $('#errors').html(response);
        }
        
      }
    })

  })


$('#passport_file').change(function(){
    readURL(this);
  });

$('#signature_file').change(function(){
    getURL(this);
  });

$('#updatePassportForm').submit(function(e){
    e.preventDefault();
  $.ajax({
        url: "script/upload-process.php",
        method: "post",
        processData: false,
        contentType: false,
        cache: false,
        // data: {file: $("#profile_file").val()},
        data: new FormData(this),
        beforeSend:function(){
          $('#updatePassport').html('Updating...');
        },
        success: function(response) {
          // console.log(response);
          if($.trim(response)==="success") {
              $('#message').html('<span class="text-success">You have updated your profile pic successfully!</span>');
          }else{
            $('#message').html(response);
          }
       },
       complete:function(){
        $('#updatePassport').html('Update');
       }


  });

  })

$('#updateSignatureForm').submit(function(e){
    e.preventDefault();
  $.ajax({
        url: "script/upload-process2.php",
        method: "post",
        processData:false,
        contentType:false,
        cache: false,
        // data: {file: $("#profile_file").val()},
        data: new FormData(this),
        beforeSend:function(){
          $('#updateSignature').html('Updating...');
        },
        success: function(response) {
          // console.log(response);
          if($.trim(response)==="success") {
              $('#messageSignature').html('<span class="text-success">You have updated your signature successfully!</span>');
          }else{
            $('#messageSignature').html(response);
          }
       },
       complete:function(){
        $('#updateSignature').html('Update');
       }
     });
   });


  });

</script>
<!-- <script type="text/javascript" src="activity.js"></script> -->
<!-- <script type="text/javascript" src="notify.js"></script> -->
