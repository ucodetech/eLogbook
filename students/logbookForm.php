<div class="row">
  <?php 
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
  <!-- table -->
  <style>
   
.form-control[readonly] {
    background-color: transparent;
}

  </style>
  <div class="col-lg-12 col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title">Logbook Form</h4>
        <p class="card-category">Log Today's Activity</p>
      </div>
      <div class="card-body">
        <hr>
        <!-- modify the condition later -->
        <?php 
        if ($friday == $sat || $friday == $sun):
         ?>
          <p class="text-center text-lg text-info"><?php 
          if($friday==$sat){
            echo 'Its Saturday!';
          }elseif($friday==$sun){
            echo 'Its Sunday!';
          } ?></p>
        <?php 
        else: 
          ?>
            <form class="form p-1" action="#" method="post" enctype="multipart/form-data" id="fillLogBook">

              <div class="form-group col-md-12">
                <div class="row">
                  <div class="col-md-3">
                  <label for="actDay">Date: <sup class="text-danger
                  ">*</sup></label>
                  <input type="text" name="actDay" id="actDay" class="form-control" value="<?=$date?>" readonly>
                  </div>
                  <div class="col-md-6">
                    <label for="activity">Activity: <sup class="text-danger
                  ">*</sup></label>
                  <textarea name="activity" id="activity" rows="3" class="form-control"></textarea>
                  </div>
                  <div class="col-md-1">
                    <label for="weekNumber">Week<sup class="text-danger
                  ">*</sup></label>
                    <input type="text" name="weekNumber" id="weekNumber" class="form-control form-control-sm" value="<?=$weekNumber?>" readonly>
                  </div>

                  <div class="col-md-2">
                    <hr class="invisible">
                    <button type="button" id="saveLogBtn" class="btn btn-sm btn-info"> <i class="fa fa-save fa-lg"></i></button>

                  </div>
                </div>
                <div class="clear-fix"></div>
                    <div id="showLogError"></div>

              </div>

            </form>
          <?php
           endif
          ;?>
            <?php 
            if ($friday == 'Fri' && $fridayTime > '3:00pm'):
             ?> 
             <hr>
             <h3 class="text-center text-primary">Write Comment</h3>
              <?php 

                if (isset($_POST['saveCommentBtn'])) {
                  $projectDone = ((isset($_POST['projectDone']) && !empty($_POST['projectDone']))?$show->test_input($_POST['projectDone']):'');
                  $stateDept = ((isset($_POST['stateDept']) && !empty($_POST['stateDept']))?$show->test_input($_POST['stateDept']):'');
                  $comments = ((isset($_POST['comments']) && !empty($_POST['comments']))?$show->test_input($_POST['comments']):'');

                $error = '';

                    if (empty($_POST['stateDept'])) {
                      $error .= $show->showMessage('danger', 'State Department/Section is required!', 'warning');
                    }
                  
                   if (empty($_POST['comments'])) {
                      $error .= $show->showMessage('danger', 'Comment  is required!', 'warning');
                    }

                    if (!empty($error)) {
                      echo $error;
                    }else{
                      $save = $db->query("UPDATE logbookOthers SET comments = '$comments', projectORjobDone = '$projectDone', section = '$stateDept' WHERE stu_unique_id = '$uniqueid'");
                      if ($save) {
                        Session::flash('saved', 'Data have been saved successfully!', 'check');
                      }
                    }


                }

               ?>
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
            <form action="" method="POST" class="p-4">
             
              <div class="row">
               <div class="form-group col-md-6">
                <label for="projectDone">Project/Job for the week if any <sup class="text-primary">*</sup></label>
                <input type="text" name="projectDone" id="projectDone" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="stateDept">State Department/Section <sup class="text-danger">*</sup></label>
                <input type="text" name="stateDept" id="stateDept" class="form-control">
              </div>
               <div class="form-group col-md-6">
                <label for="comments">Comment <sup class="text-danger">*</sup></label>
                <textarea name="comments" id="comments" class="form-control" rows="10"></textarea>
              </div>
               <div class="col-md-6">
                    <hr class="invisible">
                    <button type="submit" name="saveCommentBtn" class="btn btn-sm btn-primary"> <i class="fa fa-save fa-lg"></i></button>

                  </div>
              </div>
              <div class="row">
              <div class="form-group col-md-12">
                <div id="showErrorUpload"></div>
              </div>
            </div>
            </form>
         <?php
          endif 
         ?>
         <?php
          // if ($date == $endOfMonth): 
          ?>
          <hr>
          <h4 class="text-info text-center">Note: you are to sketche or draw activities required, then scan and upload, all in one paper</h4>
           <form action="#" id="uploadSketchesForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="week" name="week" value="<?=$weekNumber?>">
               <div class="form-group col-md-12">
                <label for="uploadSketchesFile">Upload Drawings <sup class="text-danger">*</sup></label>
                <input type="file" name="uploadSketchesFile" id="uploadSketchesFile" style="display: none">
                <label for="uploadSketchesFile" style="cursor: pointer;"><i class="fa fa-upload fa-lg text-warning" id="uploadSketchesFile"></i></label>
              </div>
              <div class="form-group col-md-6 p2 mt-1">
                <button class="btn btn-rounded btn-info" name="uploadSketches" id="uploadSketches" type="submit">Upload</button>
              </div>
              <div class="form-group col-md-12">
                <div id="showErrorUpload"></div>
              </div>
            </form>
         <?php 
       // endif 
         ?>


      </div>
    </div>
  </div>
 
</div>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title">Logs</h4>
        <p class="card-category"><?=date('M')?></p>
        <p class="card-category float-right">Week <u><?=$weekNumber?></u></p>
      </div>
      <div class="card-body">
        <hr>
           <div  id="showLogEntry"></div> 

      </div>
    </div>
  </div>
</div>