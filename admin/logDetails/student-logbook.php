<?php
  require_once '../../core/init.php';
  if (!isIsLoggedIn()){
      Session::flash('warning', 'You need to login to access that page!');
      Redirect::to('../admin-login');
  }
  
  $admin = new Admin();
  $useremail = $admin->data()->admin_email;
  $uniqueid = $admin->data()->admin_uniqueid;
  if (otpCheck()) {
    Session::flash('emailVerify', 'Please verify your email!', 'warning');
    Redirect::to('../admin-verify');
  }elseif(isOTPset($uniqueid)){
    Redirect::to('../admin-otp');
  }
  require APPROOT . '/includes/adminhead.php';
  require APPROOT . '/includes/adminnav.php';

  $general = new General();
  $db = Database::getInstance();
  $show = new Show();
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
      <div class="col-lg-12">
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
            <div class="row">
               <!-- // if get log book details returns value from database -->
              <div class="col-lg-12">
                 <!-- card body -->
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
              </div>
              <!-- // end of get log details from database -->
              <!-- student write comment and upload sketches if its friday -->
              <div class="col-lg-12">
                <?php 
             // if ($friday == 'Fri' && $fridayTime > '3:00pm'){
                   $logOthers = $admin->getLogOthers($log, $week);
                   if ($logOthers) {
                      ?>
                  <?php if ($logOthers->comments !=''): ?>
                    <!-- check if comment is empty or not -->
                  <p class="text-left"><span class="text-bold"><b>Comment: </b></span><u><?=$logOthers->comments?></u></p>
                  <p class="text-left"><span class="text-bold"><b>Project/Job Done: </b></span><u><?=$logOthers->projectORjobDone?></u></p>
                  <p class="text-left"><span class="text-bold"><b>Section/Department: </b></span><u><?=$logOthers->section?></u></p>
                 <?php endif ?>
                 <!-- end check if comment is empty -->
                 <!-- check if industrial base supervisor have certified user -->
                 <?php if ($logOthers->certifiedBy !=''): ?>
                   <p class="text-left"><span class="text-bold"><b>Certified By: </b></span><u><?=$logOthers->certifiedBy?> &nbsp; &nbsp; &nbsp;Date: <?=pretty_dates($logOthers->certifiedDate)?></u></p>
                 <?php endif ?>
                 <!-- end of check if inds have certified user -->

                 <!-- check if there is sketches  -->
                 <h3 class="text-info text-center text-underline">Description of work during Month: <u><?=date('M')?></u>  including sketches</h3>

                 


                 <?php if ($logOthers->sketches != ''): ?>
                  <div class="container" style="width: 100%; height:600px; border: 2px solid #fff;">
                      <img src="../../students/sketches/<?=$logOthers->sketches?>" alt="drawing" class="img-fluid sketche">

                 </div>
                  <hr class="hrs">
                 <?php endif ?>
                  <?php if ($logOthers->com_by_ind_sup != ''): ?>
                    <h3 class="text-center text-warning text-bold">Industrial Base Supervisor</h3>
                  <p class="text-left"><span class="text-bold"><b>Comment  by Supervisor:&nbsp;</b></span><u><?=$logOthers->com_by_ind_sup?></u></p>
                  <p class="text-left"><span class="text-bold"><b>Supervisor Name:&nbsp;</b></span><u><?=$logOthers->certifiedBy?></u></p>
                  <p class="text-left"><span class="text-bold"><b>Signature: &nbsp;</b></span> <img src="../../indusSupervisor/signature/<?=$logOthers->signature;?>" alt="signature" class="img-fluid signature"></p>
                  <p class="text-left"><span class="text-bold"><b>Designation: &nbsp;</b></span><u><?=$logOthers->designation?></u></p>
                  <?php endif; ?>
                 
                 <!-- end check sketches -->
                      <?
                   }// end if log others returns value
               // } 
               //end of if day is fri 
              ?>
              </div>
              <!-- end of student write comment if friday -->
              <!-- check if visiting supervisor from school have signed -->  
              <div class="col-lg-12">
                <h3 class="text-info text-center text-underline">Training Tutor</h3>
                <!-- check if training tutor name is in the database -->
                <?php if ($logOthers->trainName !=''): ?>
                  <p class="text-left"><span class="text-bold"><b>Comment  by Training Tutor:&nbsp;</b></span><u><?=$logOthers->train_tut_comment?></u></p>
                  <p class="text-left"><span class="text-bold"><b>Signature: &nbsp;</b></span> <img src="../signature/<?=$logOthers->trainTutSignature?>" alt="signature" class="img-fluid signature"></p>
                   <p class="text-left"><span class="text-bold"><b>Name:&nbsp;</b></span><u><?=$logOthers->trainName?></u></p>
                  <p class="text-left"><span class="text-bold"><b>Department: &nbsp;</b></span><u><?=$logOthers->trainDepartment?></u></p>
                   <p class="text-left"><span class="text-bold"><b>Date: &nbsp;</b></span><u><?=pretty_dates($logOthers->trainComDate)?></u></p>
                  <?php else: ?>
                    <?php 
                 if (isset($_POST['tutorCommentBtn'])) {
                        $comment_by_trainTut = $show->test_input($_POST['comment_by_trainTut']);
                        $trainTutSignature = $show->test_input($_POST['trainTutSignature']);
                        $name = $show->test_input($_POST['name']);
                        $department = $show->test_input($_POST['department']);
                        $student = $show->test_input($_POST['student']);
                        $week = $show->test_input($_POST['week']);
                        $dateTut = date('Y-m-d');


                        $save = $db->query("UPDATE logbookOthers SET trainName = '$name', trainDepartment = '$department', train_tut_comment = '$comment_by_trainTut', trainTutSignature = '$trainTutSignature', trainComDate = '$dateTut' WHERE stu_unique_id = '$student' AND week_number = '$week' ");
                        if ($save) {
                            Session::flash('saved', 'You have successfully assessed the student','check');

                        }
                      }
                     ?>
                <form action="" method="POST">
                  <div class="row">
                    <input type="hidden" name="student" value="<?=$logOthers->stu_unique_id?>">
                    <input type="hidden" name="week" value="<?=$week?>">

                    <div class="col-lg-6 form-group">
                      <label for="comment_by_trainTut">Comment by Training Tutor:</label>
                      <textarea name="comment_by_trainTut" rows="5" class="form-control"></textarea>
                    </div>
                     
                     <div class="col-lg-6 form-group">
                      <label for="trainTutSignature">Signature:</label>
                      <input type="text" name="trainTutSignature" id="trainTutSignature" class="form-control" value="<?=$admin->data()->admin_signature?>">
                    </div>
                    <div class="col-lg-6 form-group">
                      <label for="name">Name:</label>
                      <input type="text" name="name" id="name" class="form-control" value="<?=$admin->data()->admin_fullname?>">
                    </div>
                    <div class="col-lg-6 form-group">
                      <label for="department">Department:</label>
                      <input type="text" name="department" id="department" class="form-control" value="<?=$admin->data()->admin_department;?>">
                    </div>
                     <div class="col-lg-6 form-group">
                      <label for="ceritify">Save Change:</label>
                      <button name="tutorCommentBtn" type="submit" class="btn btn-info" id="superCertify"><i class="fa fa-save"></i></button>
                      
                    </div>
                  </div>
                </form>
                   <?php endif ?>
                   <!-- end check tutor -->
              </div>
              <!-- end check if visiting supervisor have signed -->
            </div>
           
            <!-- end of card body -->
          </div>
        </div>
        <!-- end of card -->
      </div>
     
  </div>
</div>
<!-- end of if get statment -->
<?php endif ?>

<?php
  require APPROOT . '/includes/adminfooter.php';
 ?>

