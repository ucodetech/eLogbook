<div class="row">
  <!-- table -->
  <div class="col-lg-12 col-md-12">
    <div class="card">
      <div class="card-header card-header-primary">
        <h4 class="card-title">Placement Information Form</h4>
        <p class="card-category">Fill the correctly</p>
      </div>
      <div class="card-body">
        <hr>
            <form class="form" action="#" method="post" enctype="multipart/form-data" id="addPlacementform">
              <div class="row p-4">
              <div class="form-group col-md-6">
                <label for="nameOfEstablishment">Name Of Establishment: <sup class="text-danger
                  ">*</sup></label>
                  <input type="text" name="nameOfEstablishment" id="nameOfEstablishment" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="location">Location: <sup class="text-danger
                  ">*</sup></label>
                  <input type="text" name="location" id="location" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="city">City: <sup class="text-danger
                  ">*</sup></label>
                  <input type="text" name="city" id="city" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <?php 
                    $year = $logbook->getYear();

                 ?>
                <label for="yearOperationStated">Year Operation Stated: <sup class="text-danger
                  ">*</sup></label>
                  <select name="yearOperationStated" id="yearOperationStated" class="form-control">
                    <option value="">Select Year</option>
                    
                     <?php foreach ($year as $yea): ?>
                       <option value="<?=$yea->Deyear?>"><?=$yea->Deyear?></option>
                     <?php endforeach ?>
                    
                  </select>
              </div>
              <div class="form-group col-md-6">
                <label for="principleAreaOperation">Principle Area Of Operation: <sup class="text-danger
                  ">*</sup></label>
                   <input type="text" name="principleAreaOperation" id="principleAreaOperation" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="productUndertaken">Product Undertaken (Optional)</label>
                  <input type="text" name="productUndertaken" id="productUndertaken" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label for="employmentSize">Employment Size: <sup class="text-danger
                  ">*</sup></label>
                  <input type="number" name="employmentSize" id="employmentSize" class="form-control">
              </div>
             
              <div class="form-group col-md-6">
                <button type="button" name="save" id="saveBtn" class="btn btn-info btn-block">Save</button>
                <div class="clear-fix"></div>
                <div id="showError"></div>
              </div>
            </div>
            </form>

      </div>
    </div>
  </div>
 
</div>
