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
      <div class="col-lg-8 col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">Admins</h4>
            <p class="card-category">List of admin,supervisors,swies coordinator</p>
          </div>
          <div class="card-body table-responsive"  id="admins">

          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-warning">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <span class="nav-tabs-title">Form:</span>
                <ul class="nav nav-tabs" data-tabs="tabs">
                  <li class="nav-item">
                    <a class="nav-link active" href="#profile" data-toggle="tab">
                      <i class="material-icons fa fa-user-plus fa-lg"></i> Add Admin
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
                <form class="form" action="#" method="post" enctype="multipart/form-data" id="addAdminform">
                  <div class="form-group">
                    <label for="fullname">Full Name: <sup class="text-danger
                      ">*</sup></label>
                      <input type="text" name="fullname" id="fullname" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="admin_phone_no">Phone Number: <sup class="text-danger
                      ">*</sup></label>
                      <input type="tel" name="admin_phone_no" id="admin_phone_no" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="admin_email">Email: <sup class="text-danger
                      ">*</sup></label>
                      <input type="email" name="admin_email" id="admin_email" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="password">Password: <sup class="text-danger
                      ">*</sup></label>
                      <input type="password" name="password" id="password" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="comfirm_password">Comfrim Password: <sup class="text-danger
                      ">*</sup></label>
                      <input type="password" name="comfirm_password" id="comfirm_password" class="form-control">
                  </div>

                  <div class="form-group">
                    <?php
                      $dept = $general->getDepartment();
                     ?>
                    <label for="department">Department: <sup class="text-danger
                      ">*</sup></label>
                    <select class="form-control text-light" name="department" id="department">
                      <option value="">Select Department</option>
                      <?php foreach ($dept as $dp): ?>
                          <option value="<?=strtolower($dp->department_name)?>"><?=$dp->department_name?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <?php
                      $superuser = 'superuser, supervisor, swiescoordinator, indsupervisor';
                      $supervisor = 'supervisor';
                      $swiescoordinator = 'swiescoordinator';
                      $indsupervisor = 'indsupervisor';

                     ?>
                    <label for="permission">Permissions: <sup class="text-danger
                      ">*</sup></label>
                    <select class="form-control text-light" name="permission" id="permission">
                      <option value="">Select Permission</option>
                      <option value="<?=$superuser?>">Superuser</option>
                      <option value="<?=$supervisor?>">Supervisor</option>
                      <option value="<?=$swiescoordinator?>">Swies Coordinator</option>
                      <option value="<?=$indsupervisor?>">Industrial Supervisor</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <button type="button" name="save" id="saveBtn" class="btn btn-info btn-block">Save</button>
                    <div class="clear-fix"></div>
                    <div id="showError"></div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="adminSuperStatus" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Supervision Status</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-primary" id="grabAdmin">
        
      </div>
      <div class="modal-footer">
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
    // add admin
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
    fetchAdminData();
    function fetchAdminData(){
      action = "fatchAdmins";
      $.ajax({
        url:'scripts/admin-process.php',
        method:'post',
        data:{action:action},
        success:function(response){
          $('#admins').html(response);
          $('#showAdmin').DataTable({
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





  //active supervision status
  $('body').on('click', '.adminSuperStatusIcon', function(e){
    e.preventDefault();
    uniquid = $(this).attr('u-id');
    $.ajax({
      url: 'scripts/admin-process.php',
      method: 'post',
      data: {uniquid:uniquid},
      success:function(data){
        $('#grabAdmin').html(data);
      }
    })
  });

 $('body').on('click', '.activateBtn', function(e){
    e.preventDefault();
    $.ajax({
      url: 'scripts/admin-process.php',
      method: 'post',
      data: $('#updateSupervisionStatus').serialize()+'&action=updateStatusSupervision',
      beforeSend:function(){
        $('#activateBtn').html('<img src="../../gif/trans.gif" alt="loader"> Activating..');
      },
      success:function(data){
       $('#updateSupervisionStatus')[0].reset();
       $('#adminSuperStatus').modal('hide');
       alert(data);
        
      },
       complete:function(){
        $('#activateBtn').html('Done');
      }
    })
  })




  })
</script>
