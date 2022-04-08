<div class="row">
  <?php 
    date_default_timezone_set('Africa/Lagos');
    $date = date('m/d/Y');
    $friday = date('D');
    $fridayTime = date('h:ia');
    $weekNumber =  weekOfMonth(strtotime("2021-12-19"));
    $logmonth = date('m/d/Y');
   ?>
  <!-- table -->
  <div class="col-lg-8 col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title">Logbook Form</h4>
        <p class="card-category">Log Today's Activity</p>
      </div>
      <div class="card-body">
        <hr>
            <form class="form p-1" action="#" method="post" enctype="multipart/form-data" id="addPlacementform">

              <div class="form-group col-md-12">
                <div class="row">
                  <div class="col-md-3">
                  <label for="actDay">Date: <sup class="text-danger
                  ">*</sup></label>
                  <input type="disabled" name="actDay" id="actDay" class="form-control" value="<?=$date?>" disabled>
                  </div>
                  <div class="col-md-6">
                    <label for="activity">Activity: <sup class="text-danger
                  ">*</sup></label>
                  <input type="text" name="activity" id="activity" class="form-control">
                  </div>
                  <div class="col-md-1">
                    <label for="weekNumber">Week<sup class="text-danger
                  ">*</sup></label>
                  <input type="hidden" name="logMonth" id="logMonth" class="form-control form-control-sm" value="<?=$logmonth?>" disabled>

                    <input type="disabled" name="weekNumber" id="weekNumber" class="form-control form-control-sm" value="<?=$weekNumber?>" disabled>
                  </div>

                  <div class="col-md-2">
                    <hr class="invisible">
                    <button type="button" id="saveLogBtn" class="btn btn-sm btn-info"> <i class="fa fa-save fa-lg"></i></button>
                  </div>
                </div>

              </div>

            </form>
            <?php if ($friday == 'Fri' && $fridayTime > '3:00pm'): ?> 
            <form action="#" id="uploadSketchesForm" method="POST" enctype="multipart/form-data">
               <div class="form-group col-md-12">
                <label for="uploadSketches">Upload Drawings <sup class="text-danger">*</sup></label>
                <input type="file" name="uploadSketches" id="uploadSketches" style="display: none">
                <label for="uploadSketches" style="cursor: pointer;"><i class="fa fa-upload fa-lg text-warning" id="uploadSketches"></i></label>
              </div>
              <div class="form-group col-md-12">
                <div id="showErrorUpload"></div>
              </div>
            </form>
         <?php endif ?>


      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title">Logs</h4>
        <p class="card-category"><?=date('M')?></p>
      </div>
      <div class="card-body">
        <hr>
           <div class="table-responsive" id="showLogEntry">
             
           </div> 

      </div>
    </div>
  </div>
 
</div>
