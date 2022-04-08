<?php
  require_once '../core/init.php';
  if (!isIsLoggedIn()){
      Session::flash('warning', 'You need to login to access that page!');
      Redirect::to('admin-login');
  }
  if (!hasPermissionSuper()){
      Session::flash('denied', 'You do not have permission to access that page!');
      Redirect::to('admin-dashboard');
  }
  $admin = new Admin();
  $useremail = $admin->data()->admin_email;
  $uniqueid = $admin->data()->admin_uniqueid;
  if (otpCheck()) {
    Session::flash('emailVerify', 'Please verify your email!', 'warning');
    Redirect::to('admin-verify');
  }elseif(isOTPset($uniqueid)){
    Redirect::to('admin-otp');
  }
  require APPROOT . '/includes/adminhead.php';
  require APPROOT . '/includes/adminnav.php';

  $general = new General();





 ?>
<style media="screen">
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
    <div class="row">
      <!-- table -->
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">Placement Info</h4>
            <p class="card-category">List Of Placement</p>
          </div>
          <div class="card-body table-responsive"  id="placement">

          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <!-- table -->
      <div class="col-lg-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">Supervisors</h4>
            <p class="card-category">List Supervisors</p>
          </div>
          <div class="card-body table-responsive"  id="Supervisors">

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="assignStudents" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Assign Students</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
     
      </div>
      <div class="modal-body text-primary table-responsive" id="grabStudents">
        
      </div>
      <div class="modal-footer">
           <hr class="invisible">
        <div id="showResponse"></div>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<?php
  require APPROOT . '/includes/adminfooter.php';
 ?>
<script type="text/javascript">
  $(document).ready(function(){

     //assign students
  $('body').on('click', '.assignStudentsIcon', function(e){
    e.preventDefault();
    city_id = $(this).attr('city-id');
    sup_id = $(this).attr('super-id');
    $.ajax({
      url: 'scripts/admin-process.php',
      method: 'post',
      data: {city_id:city_id,sup_id:sup_id},
      success:function(data){
        $('#grabStudents').html(data);
      }
    })
  });

 $('body').on('click', '.assignStudent', function(e){
    e.preventDefault();
     userid = $(this).attr('u-id');
     superid = $(this).attr('s-id');

    $.ajax({
      url: 'scripts/admin-process.php',
      method: 'post',
      data: {userid:userid, superid:superid},
      success:function(data){
      fatchPlacementData();
       $('#showResponse').html(data);
       setTimeout(function(){
        $('#showResponse').html('');
       }, 8000);
      }
    })
  })


    $('#saveBtn').click(function(e){
      e.preventDefault();
      role = $('#permission').val();
      $.ajax({
        url:'scripts/admin-process.php',
        method:'post',
        data:$('#addAdminform').serialize()+'&action=addAdmin',
        beforeSend:function(){
          $('#saveBtn').html('Saving...');
        },
        success:function(response){
          if (response==='success') {
              $('#addAdminform')[0].reset();
              $('#showError').html('<div id="" class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert"> &times;</button><i class="fa fa-check"></i>&nbsp; <span>'+role+' Added successfully!</span></div>');
              fetchAdminData();
              fetchSupervisorData();

          }else{
            $('#showError').html(response);
            // setTimeout(function(){
            //     $('#showError').html('');
            // },10000);

          }
        },
        complete:function(){
          $('#saveBtn').html('SAVE');
        }
      })
    });

    //fetch admin
   
    fetchSupervisorData();
    function fetchSupervisorData(){
      action = "fatchSupervisors";
      $.ajax({
        url:'scripts/admin-process.php',
        method:'post',
        data:{action:action},
        success:function(response){
          $('#Supervisors').html(response);
          $('#showSupervisors').DataTable({
              "paging": true,
              "lengthChange": true,
              "searching": true,
              "ordering": true,
              "order": [0,'desc'],
              "info": true,
              "hover": false,
              "autoWidth": true,
              "responsive": false,
              "lengthMenu": [[10,10, 25, 50, -1], [10, 25, 50, "All"]]
          });
        }
      })
    }

    // setInterval(function(){
    //   fetchAdminData();
    // },1000)

     fatchPlacementData();
    function fatchPlacementData(){
      action = "fatchPlacement";
      $.ajax({
        url:'scripts/admin-process.php',
        method:'post',
        data:{action:action},
        success:function(response){
          $('#placement').html(response);
           $('#showPlacements').DataTable({
              "paging": true,
              "lengthChange": true,
              "searching": true,
              "ordering": true,
              "order": [0,'desc'],
              "info": true,
              "hover": false,
              "autoWidth": true,
              "responsive": false,
              "lengthMenu": [[10,10, 25, 50, -1], [10, 25, 50, "All"]]
          });
         
        }
      })
    }
  })
</script>
