<div class="row">
  <!-- table -->
  <div class="col-lg-8 col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title">Industrial Base Supervisors</h4>
        <p class="card-category">List of Supervisors</p>
      </div>
      <div class="card-body table-responsive"  id="supervisors">

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
                  <i class="material-icons fa fa-user-plus fa-lg"></i> Add Industrial Base Supervisor
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
            <form class="form" action="#" method="post" enctype="multipart/form-data" id="addSupervisorform">
              <div class="form-group">
                <label for="fullname">Full Name: <sup class="text-danger
                  ">*</sup></label>
                  <input type="text" name="fullname" id="fullname" class="form-control">
              </div>
              <div class="form-group">
                <label for="phoneNo">Phone Number: <sup class="text-danger
                  ">*</sup></label>
                  <input type="tel" name="phoneNo" id="phoneNo" class="form-control">
              </div>
              <div class="form-group">
                <label for="company">Company: <sup class="text-danger
                  ">*</sup></label>
                  <input type="text" name="company" id="company" class="form-control">
              </div>
              <div class="form-group">
                <label for="company_location">Location Address: <sup class="text-danger
                  ">*</sup></label>
                  <textarea name="company_location" id="company_location" rows="5"  class="form-control"></textarea>  
              </div>
              <div class="form-group">
                <label for="comp_email">Email: <sup class="text-danger
                  ">*</sup></label>
                  <input type="email" name="comp_email" id="comp_email" class="form-control">
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
