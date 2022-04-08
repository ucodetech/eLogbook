<?php
  require_once '../../core/init.php';
  if (!hasPermissionInds()) {
      Session::flash('warning', 'You must login to access that page');
     Redirect::to('../inds-login');
    }

    $inds = new Supervisor();
    $uniqueid = $inds->superdata()->unique_id;

  if (verifyCheckInd()) {
    Session::flash('emailVerify', 'Please verify your email address!', 'warning');
    Redirect::to('../inds-verify');
  }elseif(isOTPsetUser($uniqueid)){
      Redirect::to('../inds-otp');
    }

   
  require APPROOT . '/includes/indhead.php';
  require APPROOT . '/includes/indnav.php';


  $logbook = new Logbook();
  $supervisor = new Supervisor();
  $show = new Show();
  $general = new General();
  $db = Database::getInstance();
 ?>
<style media="screen">
  .form-control{
    color: #fff;
  }
  option{
    color: #fff;
    background: #000;
  }
   .dark-edition .form-control:read-only {
    background: none;
    }
.signature{
  width: 100px;
  height:80px;
}
</style>
<?php 
if (isset($_GET['log']) && !empty($_GET['log'])): 
      $log = $_GET['log'];
      $week = $_GET['week'];
      $logget = $db->query("SELECT * FROM logbook WHERE stu_unique_id = '$log' AND week_number = '$week' ");

    date_default_timezone_set('Africa/Lagos');
    $date = date('Y-m-d');
    $friday = date('D');
    $day = date('d');
    $fridayTime = date('h:ia');
    $weekNumber =  weekOfMonth(strtotime($date));
    $logmonth = date('m/d/Y');
    $demoDate = date('2022-01-01');

    $sat = 'Sat'; 
    $sun = 'Sun';


    // check is the date is end of the month
    $endOfMonth = endOFMonth($date);


   ?>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- table -->
      <div class="col-lg-12 col-md-12">
        <div class="card" style="display:block;">
          <div class="card-header card-header-primary">
            <h4 class="card-title">Logbook Details</h4>
            <p class="card-category">log</p>
             <?php if (Session::exists('saved')): ?>
             <div class="alert alert-info alert-dismissible">
                 <button type="button" class="close" data-dismiss="alert">
                &times;
                </button>
         <i class="fa fa-warning"></i>&nbsp;
                 <strong class="text-left">
                     <?=Session::flash('saved') ?>
                 </strong>
             </div>
         <?php endif ?>
          </div>
          <div class="card-body">
            <?php 

             if ($logget->count()) {
                $rows = $logget->results();
                ?>
                <?php foreach ($rows as $row): ?>
              <div class="row">
               <div class="col-lg-2 text-left"  style="border:2px solid grey;">
                 <strong class="text-center">
                  <?=pretty_dayLetterd($row->log_month)?>
                 </strong><br>
                 <span class="text-center">
                 <?=pretty_dates($row->log_month)?>
               </span>
               </div>
               <div class="col-lg-9 text-left"  style="border:2px solid grey;">
                 <stong class="text-left">
                  <?=$row->activity?></stong>
                </div>
                <div class="col-lg-1  text-right" style="border:2px solid grey;">
                <u>Week: <?=$row->week_number?></u>
                </div>
              </div>
              
             

               <?php endforeach ?>
                <?
               }


             ?>
              <?php 
            // if ($friday == 'Fri' && $fridayTime > '3:00pm'): 
              $logOthers = $supervisor->getLogOthers($log, $week);
              if ($logOthers) {
                  ?>
              <div class="row p-1 m-1">
                <div class="col-lg-12">
                <?php if ($logOthers->comments !=''): ?>
                  <p class="text-left"><span class="text-bold"><b>Comment:</b></span><u><?=$comments?></u></p>
                  <p class="text-left"><span class="text-bold"><b>Project/Job Done:</b></span><u><?=$logOthers->projectORjobDone?></u></p>
                  <p class="text-left"><span class="text-bold"><b>Section/Department:</b></span><u><?=$logOthers->section?></u></p>
               <?php endif ?>
                  <?php if ($logOthers->certifiedBy !=''): ?>
                  <p class="text-left"><span class="text-bold"><b>Certified By:</b></span><u><?=$logOthers->certifiedBy?> &nbsp; &nbsp; &nbsp;Date: <?=pretty_dates($logOthers->certifiedDate)?></u></p>
                 <?php else: ?>
                  <hr class="hrs">
                  <?php 
                      if (isset($_POST['superCertify'])){
                        $certify = $show->test_input($_POST['certify']);
                        $student = $show->test_input($_POST['student']);
                        $week = $show->test_input($_POST['week']);


                        $date = date('Y-m-d'); 

                        $save = $db->query("UPDATE logbookOthers SET certifiedBy = '$certify', certifiedDate = '$date' WHERE stu_unique_id = '$student' AND week_number = '$week' ");
                        if ($save) {
                            Session::flash('saved', 'You have successfully certified this student', 'check');

                        }
                    }
                   ?>
                <form action="" method="POST" id="superSignForm">
                  <div class="row">
                    <div class="form-group">
                      <label for="ceritify">Certify:</label>
                      <input type="hidden" name="student" value="<?=$logOthers->stu_unique_id?>">
                    <input type="hidden" name="week" value="<?=$week?>">

                      <input type="text" name="certify" id="certify" class="form-control" value="<?=$supervisor->superdata()->fullname;?>" readonly>
                    </div>
                     <div class="form-group">
                      <label for="ceritify">Save Change:</label>
                      <button type="submit" name="superCertify" class="btn btn-info" id="superCertify"><i class="fa fa-save"></i></button>
                      
                    </div>
                  </div>
                </form>
                  <?php endif ?>
                </div>
                <!-- check if there is sketches then allow inds supervisor comment  -->

                <div class="col-lg-12">
                  <?php if ($endOfMonth): ?>
                  <?php if ($logOthers->sketches != ''): ?>
                  <img src="../../students/sketches/<?=$logOthers->sketches?>" alt="drawing" class="img-fluid sketche">
                  <hr class="hrs">
                 <?php endif ?>
                <?php if ($logOthers->certifiedBy !=''): ?>
                  <?php if ($logOthers->com_by_ind_sup != ''): ?>
                  <p class="text-left"><span class="text-bold"><b>Comment  by Supervisor:&nbsp;</b></span><u><?=$logOthers->com_by_ind_sup?></u></p>
                  <p class="text-left"><span class="text-bold"><b>Supervisor Name:&nbsp;</b></span><u><?=$logOthers->certifiedBy?></u></p>

                  <p class="text-left"><span class="text-bold"><b>Signature: &nbsp;</b></span> <img src="../signature/<?=$inds->superdata()->signature?>" alt="signature" class="img-fluid signature"></p>
                  <p class="text-left"><span class="text-bold"><b>Designation: &nbsp;</b></span><u><?=$logOthers->designation?></u></p>
                  <?php else: ?>
                    <?php 
                      if (isset($_POST['superCertify'])) {
                        $com_by_ind_sup = $show->test_input($_POST['com_by_ind_sup']);
                        $supervisorSignature = $show->test_input($_POST['supervisorSignature']);
                        $designation = $show->test_input($_POST['designation']);
                        $student = $show->test_input($_POST['student']);
                        $week = $show->test_input($_POST['week']);

                        $save = $db->query("UPDATE logbookOthers SET com_by_ind_sup = '$com_by_ind_sup', signature = '$supervisorSignature', designation = '$designation' WHERE stu_unique_id = '$student' AND week_number = '$week' ");
                        if ($save) {
                            Session::flash('saved', 'You have successfully certified this student', 'check');

                        }
                      }
                     ?>
                <form action="" method="POST" id="superCommentForm">
                  <div class="row">
                    <input type="hidden" name="student" value="<?=$logOthers->stu_unique_id?>">
                    <input type="hidden" name="week" value="<?=$week?>">

                    <div class="col-lg-6 form-group">
                      <label for="com_by_ind_sup">Comment by Supervisor:</label>
                      <textarea name="com_by_ind_sup" rows="5" class="form-control"></textarea>
                    </div>
                     <div class="col-lg-6 form-group">
                      <label for="supervisorSignature">Supervisor Signature:</label>
                      <input type="text" name="supervisorSignature" id="supervisorSignature" class="form-control" value="<?=$supervisor->superdata()->signature?>">
                    </div>
                    <div class="col-lg-6 form-group">
                      <label for="designation">Designation:</label>
                      <input type="text" name="designation" id="designation" class="form-control">
                    </div>
                     <div class="col-lg-6 form-group">
                      <label for="ceritify">Save Change:</label>
                      <button name="superCertify" type="submit" class="btn btn-info" id="superCertify"><i class="fa fa-save"></i></button>
                      
                    </div>
                  </div>
                </form>
                  <?php endif ?>
                   <?php endif ?>
                   <!-- end check end of the month -->
                   <?php endif ?>

               </div>
                <!-- end of check if its end of the month so student can upload sketches and allow inds supervisor comment -->
              </div>



                  <?
              }
              ?> 
            
            <?php
             // endif 
            ?>
          </div>
        </div>
       
      </div>
      
    </div>
  </div>
</div>

<?php endif ?>


<?php
  require APPROOT . '/includes/adminfooter.php';
 ?>

