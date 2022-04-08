<?php


class Counsel
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::getInstance();
    }

    public function create($table,$field = array()){
        if(!$this->_db->insert($table, $field)){
            throw new Exception("Error Processing form", 1);
        }
    }

    public function update($table,$detilid,$field = array()){
        if(!$this->_db->update($table,'id',$detilid, $field)){
            throw new Exception("Error updating form", 1);
        }
    }

    public function getTable($table, $val, $field){
        return $this->_db->get($table, array($val, '=', $field));
    }

    public function fetchCounselling(){
        $output = '';
        $counsel = $this->getTable('counsellingForm','deleted',0);
        if ($counsel->count()) {
            $grabbed = $counsel->results();

            $output .= '
      <table class="table table-striped table-hover" id="showCounselling">
        <thead>
          <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Phone No</th>
            <th>Department</th>
            <th>Level</th>
            <th>Date Filled</th>
            <th>Action</th>


          </tr>
        </thead>
        <tbody>
      ';
            foreach ($grabbed as $row) {
                $full_name = $row->surname . $row->othernames;
                $output .= '
            <tr>
         <td>'.$row->id.'</td>
         <td>'.$full_name.'</td>
          <td>'.$row->phoneNo.'</td>
         <td>'.$row->department.'</td>
         <td>'.$row->level.'</td>
         <td>'.pretty_dates($row->dateApplied).'</td>
           <td>
           <a href="detail/counsel-detail/'.$row->id.'" class="btn btn-sm btn-primary"><i class="fa fa-info-circle fa-lg"></i>&nbsp;Details</a>
           
           </td>
            </tr>
            ';
            }

            $output .= '
        </tbody>
      </table>';

            return  $output;
        }else{
            return  '<h3 class="text-center text-secondary align-self-center lead">No Data Yet</h3>';
        }

    }

    public function triggerForm($formName, $value){
        $data = $this->_db->get('triggerTable' , array($formName, '=', $value));
        if ($data->count()){
            return $data->first();
        }else{
            return false;
        }
}


    public function updateTrigger($val,$formName){
    $sql = "UPDATE triggerTable SET switch = '$val' WHERE formName = '$formName'";
    $this->_db->query($sql);
        return true;
}

    public function fetchScreening(){
        $output = '';
        $screening = $this->getTable('screeningForm','deleted',0);
        if ($screening->count()) {
            $grabbed = $screening->results();

            $output .= '
      <table class="table table-striped table-hover" id="showscreening">
        <thead>
          <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Phone No</th>
            <th>Department</th>
            <th>Level</th>
            <th>Date Filled</th>
            <th>Action</th>


          </tr>
        </thead>
        <tbody>
      ';
            foreach ($grabbed as $row) {
                $full_name = $row->surname .'  '. $row->othernames;
                $output .= '
            <tr>
         <td>'.$row->id.'</td>
         <td>'.$full_name.'</td>
          <td>'.$row->phoneNo.'</td>
         <td>'.$row->department.'</td>
         <td>'.$row->level.'</td>
         <td>'.pretty_dates($row->dateApplied).'</td>
           <td>
           <a href="detail/screening-detail/'.$row->id.'" class="btn btn-sm btn-primary"><i class="fa fa-info-circle fa-lg"></i>&nbsp;Details</a>
           &nbsp;&nbsp;&nbsp;
           <button class="btn btn-danger btn-sm showMakeExco" id="'.$row->id.'" type="button" data-toggle="modal" data-target="#makeExcoModal"><i class="fa fa-check fa-lg"></i>Make Exco</button>
           </td>
            </tr>
            ';
            }

            $output .= '
        </tbody>
      </table>';

            return  $output;
        }else{
            return  '<h3 class="text-center text-secondary align-self-center lead">No Data Yet</h3>';
        }

    }

    public  function checkUser($table, $userid){
        $user = $this->_db->get($table, array('user_id', '=', $userid));
        if ($user->count()){
            return true;
        }else{
            return false;
        }


}

    public function getDetail($table, $detailid){
        $get = $this->_db->get($table, array('id','=', $detailid));
        if ($get->count()){
            return $get->first();
        }else{
            return false;
        }
}

    public function  getScreenDetail($exco_id){
        $get = $this->_db->get('screeningForm', array('id','=', $exco_id));
        if ($get->count()){
            return $get->first();
        }else{
            return false;
        }
}




}//end of class