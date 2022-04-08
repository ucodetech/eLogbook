<?php

 /**
  * General
  */
 class General
 {

 	private  $_db,
           $_user,
           $_super;


  function __construct()
  {
    $this->_db = Database::getInstance();
   $this->_user = new User() ;
   $this->_super = new Admin();

  }

  public function superNow()
  {
   return $this->_super;
  }


 public function getImgSuper($superimgid){
        $data = $this->_db->get('super_profile', array('sudo_id', '=', $superimgid));
     	  if ($this->_db->count()) {
     	  	return $this->_db->first();
     	  }else{
     	  	return false;
     	  }
    }


  public function updateAdmin(){
       $super = $this->superNow()->getAdminId();
        $sql = "UPDATE admin SET sudo_lastLogin = NOW() WHERE id = '$super' ";
        $this->_db->query($sql);
        return true;
    }


  public function totalCount($tablename){
    $sql = "SELECT * FROM $tablename";
    $count =  $this->_db->query($sql);
    if ($count->count()) {
      return $count->count();
    }else{
      return '0';
    }
  }

     public function totalCountApproved($tablename, $val){
         $sql = "SELECT * FROM $tablename WHERE approved = $val AND deleted = 0 ";
         $count =  $this->_db->query($sql);
         if ($count->count()) {
             return $count->count();
         }else{
             return '0';
         }
     }


  public function verified_admin($status){
    $count =  $this->_db->get('admin', array('sudo_verified', '=', $status));
    if ($count->count()) {
      return $count->count();
    }else{
      return '0';
    }
  }




  //Reply to user feedback
public function replyFeedback($userid, $message){
    $this->_db->insert('notifications', array(
      'user_id' => $userid,
      'type' => 'user',
      'message' => $message
    ));
    return true;
  }


public function updateFeedbackReplied($feedid){
    $this->_db->update('feedback','id', $feedid , array(
      'replied' => 1
    ));
    return true;
  }



  public function fetchAdmins($val){
    $output = '';
    $imgPath = '../chapel_Admin/profile/';


    $this->_db->get('admin', array('suspended', '=', $val));
    if ($this->_db->count()) {
      $dat = $this->_db->results();

      $output .= '
      <table class="table table-striped table-hover" id="showAdmin">
        <thead>
          <tr>
            <th>#</th>
            <th>Profile</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Date Added</th>
            <th>Last Login</th>
            <th>Email Verified</th>
            <th>Detail</th>
            <th>Trash</th>


          </tr>
        </thead>
        <tbody>
      ';
      foreach ($dat as $row) {

          $passport = '<img src="'.$imgPath.$row->passport.'"  alt="User Image" width="70px" height="70px" style="border-radius:50px;">';

        if($row->sudo_verified == 0){
            $msg ='<span class="text-danger align-self-center lead">No</span>';
        }else{

          $msg ='<span class="text-success align-self-center lead">Yes</span>';

        }
        $admin = new Admin();
        if($row->id == $admin->getAdminId()){
            $action ='<span class="text-success align-self-center lead">superuser</span>';
        }else{
          $action ='<a href="#" id="'.$row->id.'" title="Trash Admin" class="btn btn-sm btn-danger deleteStudentIcon">Trash </a>';


        }
        $output .= '
            <tr>
              <td>'.$row->id.'</td>
                <td>'.$passport.'</td>
                     <td>'.$row->sudo_full_name.'</td>
                         <td>'.$row->sudo_email.'</td>
                         <td>'.pretty_dates($row->sudo_dateAdded).'</td>
                         <td>'.pretty_dates($row->sudo_lastLogin).'</td>

                           <td>'.$msg.'</td>
                           <td>

                           <a href="detail/admin-detail/'.$row->id.'" id="'.$row->id.'" title="View Details" class="btn btn-sm btn-info">Detail </a>
                           </div>
                           &nbsp;&nbsp;

                           </td>
                           <td>
                           '.$action.'
                           </td>
            </tr>
            ';
      }



      $output .= '
        </tbody>
      </table>';

        return  $output;
  }else{
        return  '<h3 class="text-center text-secondary align-self-center lead">No Admin In database</h3>';
    }


  }


  public function getadminDetail($admin_id)
    {
      $admin = $this->_db->get('admin', array('id', '=', $admin_id));
      if ($admin->count()) {
        return  $admin->first();

      }else{
        return false;
      }
    }



public function getDepartment()
{
  $dept = $this->_db->get('department', array('deleted', '=', 0));
  if ($dept->count()) {
      return $dept->results();
  }else {
    return false;
  }
}
     public function getSchool()
     {
         $sch = $this->_db->get('schoolsTable', array('deleted', '=', 0));
         if ($sch->count()){
             return $sch->results();
         }else {
             return false;
         }
     }
 public function getState()
     {
         $state = $this->_db->get('states', array('deleted', '=', 0));
         if ($state->count()){
             return $state->results();
         }else {
             return false;
         }
     }
 public function getLGA()
     {
         $lga = $this->_db->get('lga', array('deleted', '=', 0));
         if ($lga->count()){
             return $lga->results();
         }else {
             return false;
         }
     }



 public function getUnit()
     {
         $unit = $this->_db->get('chapel_units', array('deleted', '=', 0));
         if ($unit->count()){
             return $unit->results();
         }else {
             return false;
         }
     }

public function getGeneral()
{
  $unit = $this->_db->get('general', array('id', '=', 1));
         if ($unit->count()){
             return $unit->first();
         }else {
             return false;
         }
}

public function updateStudentRecored($user_id, $field, $value)
{
  $this->_db->update('students', 'id', $user_id, array(
          $field => $value

        ));

        return true;
}

public function InsertSignature($user_id, $user_signature)
{
  $this->_db->insert('signatures',  array(
          'user_id' => $user_id,
          'user_signature' => $user_signature

        ));

        return true;
}

public function fetchstudents($val){
         $output = '';
         $imgPath = 'profile/';


         $this->_db->get('students', array('approved', '=', $val));
         if ($this->_db->count()) {
             $dat = $this->_db->results();

             $output .= '
      <table class="table table-striped table-hover" id="Shownewstudents">
        <thead>
          <tr>
            <th>#</th>
            <th>Profile</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Level</th>
            <th>Department</th>
            <th>Date Joined</th>
            <th>Action</th>


          </tr>
        </thead>
        <tbody>
      ';
             foreach ($dat as $row) {

                 $passport = '<img src="'.$imgPath.$row->passport.'"  alt="User Image" width="70px" height="70px" style="border-radius:50px;">';

                 $output .= '
            <tr>
          <td>'.$row->id.'</td>
            <td>'.$passport.'</td>
         <td>'.$row->full_name.'</td>
         <td>'.$row->email.'</td>
          <td>'.$row->level.'</td>
         <td>'.$row->department.'</td>

         <td>'.pretty_dates($row->dateJoined).'</td>
           <td>
           <button id="'.$row->id.'" class="btn btn-sm btn-info approveMemberBtn">Approve</button>

           </td>
            </tr>
            ';
             }

             $output .= '
        </tbody>
      </table>';

             return  $output;
         }else{
             return  '<h3 class="text-center text-secondary align-self-center lead">No New Member</h3>';
         }


     }


public function fetchstudentsApproved(){
         $output = '';
         $imgPath = URLROOT.'chapel_students/profile/';


         $this->_db->get('students', array('approved', '=', 1));
         if ($this->_db->count()) {
             $dat = $this->_db->results();

             $output .= '
      <table class="table table-striped table-hover" id="ShowstudentsApproved">
        <thead>
          <tr>
            <th>#</th>
            <th>Profile</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Level</th>
            <th>Department</th>
            <th>Date Joined</th>
            <th>Action</th>


          </tr>
        </thead>
        <tbody>
      ';
             foreach ($dat as $row) {

                 $passport = '<img src="'.$imgPath.$row->passport.'"  alt="User Image" width="70px" height="70px" style="border-radius:50px;">';

                 $output .= '
            <tr>
          <td>'.$row->id.'</td>
            <td>'.$passport.'</td>
         <td>'.$row->full_name.'</td>
         <td>'.$row->email.'</td>
          <td>'.$row->level.'</td>
         <td>'.$row->department.'</td>

         <td>'.pretty_dates($row->dateJoined).'</td>
           <td>
           <a href="detail/member-detail/'.$row->id.'" class="btn btn-sm btn-primary"><i class="fa fa-edit fa-lg"></i>&nbsp;Details</a>

           </td>
            </tr>
            ';
             }

             $output .= '
        </tbody>
      </table>';

             return  $output;
         }else{
             return  '<h3 class="text-center text-secondary align-self-center lead">No New Member</h3>';
         }


     }



public function approveMember($approveid){
      $one = 1;
      $this->_db->update('students','id',$approveid, array(
         'approved' => $one
      ));
      return true;
    }

public function getMemberDetail($detail_id){
      $data = $this->_db->get('students', array('id','=',$detail_id));
      if ($data->count()){
          return $data->first();
      }else{
          return false;
      }
    }

public function updateStudents($memberId,$fields = array()){
      $this->_db->update('students','stud_unique_id', $memberId,$fields);
      return true;

}


public function fetchVisitors(){
         $output = '';

         $this->_db->get('chapel_visitors', array('deleted', '=', 0));
         if ($this->_db->count()) {
             $dat = $this->_db->results();

             $output .= '
      <table class="table table-striped table-hover" id="ShowVisitorsT">
        <thead>
          <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone No</th>
            <th>Invited By</th>
            <th>Want to be Member</th>
            <th>Date Visited</th>
            <th>Action</th>


          </tr>
        </thead>
        <tbody>
      ';
             foreach ($dat as $row) {

                 $output .= '
            <tr>
         <td>'.$row->id.'</td>
         <td>'.$row->full_name.'</td>
         <td>'.$row->email.'</td>
          <td>'.$row->phoneNo.'</td>
         <td>'.$row->invited_by.'</td>
         <td>'.$row->become_member.'</td>
         <td>'.pretty_dates($row->dateVisited).'</td>
           <td>
           <a href="detail/visitor-detail/'.$row->id.'" class="btn btn-sm btn-primary"><i class="fa fa-info-circle fa-lg"></i>&nbsp;Details</a>

           </td>
            </tr>
            ';
             }

             $output .= '
        </tbody>
      </table>';

             return  $output;
         }else{
             return  '<h3 class="text-center text-secondary align-self-center lead">No New Member</h3>';
         }


     }


public function getVisitorDetail($detail_id){
         $data = $this->_db->get('chapel_visitors', array('id','=',$detail_id));
         if ($data->count()){
             return $data->first();
         }else{
             return false;
         }
     }

     //fetch student excos
     public function fetchStudentExco(){
         $output = '';

         $this->_db->get('chapel_visitors', array('deleted', '=', 0));
         $sql = "SELECT * FROM studentExcos WHERE deleted = 0 ORDER BY SchoolSession DESC";
         $this->_db->query($sql);
         if ($this->_db->count()) {
             $dat = $this->_db->results();

             $output .= '
      <table class="table table-striped table-condensed table-hover" id="ShowstudentExeco">
        <thead>
          <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Office</th>
            <th>Phone No</th>
            <th>Department</th>
            <th>Level</th>
            <th>Session</th>
            <th>Action</th>


          </tr>
        </thead>
        <tbody>
      ';
             foreach ($dat as $row) {
                $user = new User($row->user_id);
                $userFullname = $user->data()->full_name;
                $userEmail = $user->data()->email;
                 $userPhone = $user->data()->mobile;
                 $userDepartment = $user->data()->department;
                 $userLevel = $user->data()->level;



                 $output .= '
            <tr>
         <td>'.$row->id.'</td>
         <td>'.$userFullname.'</td>
         <td>'.$row->office.'</td>
          <td>'.$userPhone.'</td>
         <td>'.$userDepartment.'</td>
         <td>'.$userLevel.'</td>
         <td>'.$row->SchoolSession.'</td>
           <td>
           <a href="detail/member-detail/'.$row->user_id.'" class="btn btn-sm btn-primary"><i class="fa fa-info-circle fa-lg"></i>&nbsp;Member Details</a>

           </td>
            </tr>
            ';
             }

$output .= '
        </tbody>
      </table>';

return  $output;
}else{
    return  '<h3 class="text-center text-secondary align-self-center lead">No Data yet</h3>';
}


}

//fetch chapel council executives

    /**
     * @return Database|null
     */
    public function getCouncilExco()
    {
        $data = $this->_db->get('councilstudents', array('deleted', '=', 0));
        if ($data->count()){
            return $data->results();
        }else{
            return false;
        }
    }


    public function loggedUsers(){
        $sql = "SELECT * FROM students WHERE stud_last_login > DATE_SUB(NOW(), INTERVAL 5 SECOND)";
        $data = $this->_db->query($sql);
        if ($data->count()) {
            return $data->results();
        }else{
            return false;
        }
    }

    public function updateAdminRecored($admin_id, $field, $value)
    {
      $this->_db->update('admin', 'id', $admin_id, array(
          $field => $value

        ));

        return true;
    }



}//end of class
