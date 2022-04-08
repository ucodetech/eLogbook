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


  $logbook = new Logbook();
  $show = new Show();
  $db = Database::getInstance();


 ?>
<style media="screen">
.activeImg{
  width: 70px;
  height: 70px;
  border-radius: 50%;
}
.card-title{
  color:#fff !important;
}
.form-control{
  color: #fff;
}
option{
  color: #fff;
  background: #000;
}
</style>
<div class="content">
  <div class="container-fluid">
    <!-- first role monitor users -->
    <!-- check student have filled placement form -->
    <?php if (!$logbook->checkPlacement($uniqueid)): ?>
     <?php include 'placementForm.php';?>
    <?php else: ?>
    <?php include 'logbookForm.php' ?>
  <?php endif ?>
  <!-- end check here -->


  </div>
</div>

<?php
  require APPROOT . '/includes/stfooter.php';
 ?>

 <script>
 function getURL(input){

    if (input.files && input.files[0]) {
       var reader = new FileReader();
      reader.onload = function(e){
        $('#showErrorUpload').html('<img src="'+e.target.result+'" alt="preview" class="img-fluid sketchPreview">');
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
     $(document).ready(function(){


$('#uploadSketchesFile').change(function(){
    getURL(this);
  });


         $('#uploadSketchesForm').submit(function (e){
             e.preventDefault();
             $.ajax({
                 url:'script/uploadDraw-process.php',
                 method:'post',
                 processData: false,
                 contentType:false,
                 cache:false,
                 data: new FormData(this),
                 success:function (response){
                     if (response==='success'){
                         $('#uploadSketchesForm')[0].reset();
                         alert('Success');
                         location.reload();
                     }else{
                         alert(response);
                     }
                 }
             })
         });

         //fetch logs
         fetch_log();
         function fetch_log(){
             action = 'fetchLogs';
             $.ajax({
                 url:'script/log-process.php',
                 method:'post',
                 data:{action:action},
                 success:function (response){
                     console.log(response);
                     $('#showLogEntry').html(response);
                    
                 }
             });
         }

     $('#saveLogBtn').click(function(e){
         e.preventDefault();
         Swal.fire({
             title: 'Are you sure?',
             text: "You are about to save this activity! there won\'t be room for editing!",
             type: 'warning',
             showCancelButton: true,
             confirmButtonColor: '#3085d6',
             cancelButtonColor: '#d33',
             confirmButtonText: 'Yes, Save it!'
         }).then((result) => {
             if (result.value) {
                 $.ajax({
                     url:'script/log-process.php',
                     method: 'POST',
                     data:$('#fillLogBook').serialize()+'&action=addActivity',
                     success:function(response){
                        if (response==='success') {
                            $('#fillLogBook')[0].reset();
                            Swal.fire(
                             'Today\'s Activity have been saved',
                             'Activity Saved Successfully',
                             'success'
                         );
                         fetch_log();
                        }else{
                            $('#showLogError').html(response);
                        }
                         
                     }
                 });

             }
         });
     })


   

         // // add supervisor
         // $('#saveLogBtn').click(function(e){
         //   e.preventDefault();
         //   $.ajax({
         //     url:'script/log-process.php',
         //     method:'post',
         //     data:$('#fillLogBook').serialize()+'&action=addActivity',
         //     beforeSend:function(){
         //       $('#saveBtn').html('Saving...');
         //     },
         //     success:function(response){
         //       if (response==='success') {
         //           $('#fillLogBook')[0].reset();
         //           $('#showError').html('<div id="" class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert"> &times;</button><i class="fa fa-check"></i>&nbsp; <span>Your form have been submitted successfully</span></div>');
         //          setTimeout(function(){
         //            location.reload();
         //         },3000);
         //       }else{
         //         $('#showError').html(response);
         //         // setTimeout(function(){
         //         //     $('#showError').html('');
         //         // },10000);

         //       }
         //     },
         //     complete:function(){
         //       $('#saveBtn').html('SAVE');
         //     }
         //   })
         // });

     });
 </script>
<!--  <script type="text/javascript" src="scripts.js"></script>
 <script type="text/javascript" src="activity.js"></script> -->
 <!-- <script type="text/javascript" src="notify.js"></script> -->
