 <div class="col-md-12">
    <div class="card">
      <div class="card-header card-header-tabs card-header-warning">
        <div class="nav-tabs-navigation">
          <div class="nav-tabs-wrapper">
            <span class="nav-tabs-title">Search</span>
            <ul class="nav nav-tabs" data-tabs="tabs">
              <li class="nav-item">
                <a class="nav-link active" href="#profile" data-toggle="tab">
                  <i class="material-icons fa fa-search fa-lg"></i> Search By (Full Date) or (Week Number)
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
            <form class="form" action="#" method="POST" id="viewLogbookForm">
                <input type="hidden" name="assignedSuper" id="assignedSuper" value="<?=$admin->data()->admin_uniqueid?>">
              <div class="form-group">
                <label for="unique_id">Student: <sup class="text-danger
                  ">*</sup></label>
                  <select name="unique_id" id="unique_id" class="form-control">
                    <option value="">Select Student</option>
                    <?php 
                        $studentid = $admin->grabStudentsUnderMe2($admin->data()->admin_uniqueid);
                    foreach ($studentid as $suid): ?>
                        <option value="<?=$suid->stud_unique_id?>"><?=$suid->stud_fname. ' ' . $suid->stud_lname;?></option>
                    <?php endforeach ?>
                  </select>
              </div>
              <div class="row">
                  <div class="form-group col-md-6">
                <label for="search_date">Date: <sup class="text-danger
                  ">*</sup></label>
                  <input type="date" name="search_date" id="search_date" class="form-control">
                 
              </div>
              <div class="form-group col-md-6">
                <label for="search_week">Week:<sup class="text-danger
                  ">*</sup></label>
                  <select class="form-control" name="search_week" id="search_week">
                    <option value="">Select Week Number</option>
                    <?php 
                   $week = array('1','2','3','4','5');
                    foreach ($week as $w): ?>
                    <option value="<?=$w?>"><?=$w;?></option>
                    <?php endforeach ?>
                  </select>
              </div>
              </div>
              <div class="form-group">
                <button type="button" name="search" id="search" class="btn btn-info btn-block">Search</button>
                <div class="clear-fix"></div>
                <div id="err" class="p-3 my-2">
                  
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <hr class="hrs">
  <!-- table -->
  <div class="col-lg-12" id="studentUnderSupervisor">
    <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title">Students Under Me</h4>
        <p class="card-category">List of Students Under Me</p>
      </div>
      <div class="card-body table-responsive"  id="studentsUnderme">

      </div>
    </div>
  </div>
 
