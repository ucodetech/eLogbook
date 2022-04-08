<?php
  require_once '../core/init.php';
  if (!hasPermissionInds()) {
      Session::flash('warning', 'You must login to access that page');
     Redirect::to('inds-login');
    }

    $inds = new Supervisor();
    $uniqueid = $inds->superdata()->unique_id;

  if (verifyCheckInd()) {
    Session::flash('emailVerify', 'Please verify your email address!', 'warning');
    Redirect::to('inds-verify');
  }elseif(isOTPsetUser($uniqueid)){
      Redirect::to('inds-otp');
    }

   
  require APPROOT . '/includes/indhead.php';
  require APPROOT . '/includes/indnav.php';


  $logbook = new Logbook();


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
     <?php include 'supervisorStudents.php';?>

  <!-- end check here -->


  </div>
</div>

<?php
  require APPROOT . '/includes/indfooter.php';
 ?>
 <script>
     $(document).ready(function(){

        // fetch students under me 
          fetch_StudentsUnderMe();
         function fetch_StudentsUnderMe(){
             action = 'fetchStudentsUnderMe';
             $.ajax({
                 url:'script/super-process.php',
                 method:'post',
                 data:{action:action},
                 success:function (response){
                     // console.log(response);
                     $('#studentsUnderme').html(response);
                 }
             });
         }


         $('#search').click(function(e){
            e.preventDefault();
            $.ajax({
              url:'script/super-process.php',
              method:'POST',
              data:$('#viewLogbookForm').serialize()+'&action=searchLogbook',
              beforeSend:function(){
                $('#search').html('Searching...');
              },
              success:function(data){
                // console.log(data);
                $('#err').html(data);
              },
              complete:function(){
                $('#search').html('Search');
              }
            })  
           
         })


     });
 </script>
<!--  <script type="text/javascript" src="scripts.js"></script>
 <script type="text/javascript" src="activity.js"></script> -->
 <!-- <script type="text/javascript" src="notify.js"></script> -->
