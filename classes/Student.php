<?php

 /**
  * Student
  */
 class Student
 {

 	private  $_db,
           $_user,
           $_super;


  function __construct()
  {
    $this->_db = Database::getInstance();
    $this->_user = new User() ;

  }


  //Get gender percentage
public function genderPer(){
  $sql = "SELECT gender, COUNT(*) AS number FROM students WHERE gender != '' GROUP BY gender ";
  $data = $this->_db->query($sql);
	  if ($data->count()) {
	  	return $data->results();
	  }else{
	  	return false;
	  }
}

// verified and unverified percenta
public function verifiedPer(){
  $sql = "SELECT verified, COUNT(*) AS number FROM students  GROUP BY verified ";
   $data = $this->_db->query($sql);
	  if ($data->count()) {
	  	return $data->results();
	  }else{
	  	return false;
	  }
}



 public function getImgSuper($superimgid){
        $data = $this->_db->get('super_profile', array('sudo_id', '=', $superimgid));
     	  if ($this->_db->count()) {
     	  	return $this->_db->first();
     	  }else{
     	  	return false;
     	  }
    }


  public function verified_users($status){
    $count =  $this->_db->get('students', array('verified', '=', $status));
    if ($count->count()) {
      return $count->count();
    }else{
      return '0';
    }
  }


public function fetchUserDetail($id){
    $data = $this->_db->get('students', array('stu_id', '=', $id));
    if ($data->count()) {
      return $data->first();
    }else{
      return false;
    }
}




public function loggedUsers(){
    $sql = "SELECT * FROM students WHERE lastLogin > DATE_SUB(NOW(), INTERVAL 5 SECOND)";
    $data = $this->_db->query($sql);
  	  if ($data->count()) {
  	  	return $data->results();
  	  }else{
  	  	return false;
  	  }
  }


public function updateStudentRecored($student_id, $field, $value)
{
	$this->_db->update('students', 'stu_id', $student_id, array(
    	$field => $value

    ));

    return true;
}




public function fetchStudents(){
  $output = '';
  $imgPath = '../students/profile/';

  $sql = "SELECT * FROM students WHERE deleted = 0 AND suspened = 0";
  $query = $this->_db->query($sql);
  if ($query->count()) {
    $dat = $query->results();
  if ($dat) {
    $output .= '
    <table class="table table-striped table-hover" id="showStudent">
      <thead>
        <tr>
          <th>#</th>
          <th>Photo</th>
          <th>Full Name</th>
          <th>Matric Number</th>
          <th>Department</th>
          <th>Level</th>
          <th>Joined Date</th>
          <th>Last Login</th>
          <th>Action</th>

        </tr>
      </thead>
      <tbody>
    ';
    foreach ($dat as $row) {

        $passport = '<img src="'.$imgPath.$row->passport.'"  alt="User Image" width="70px" height="70px" style="border-radius:50px;">';

      $output .= '
          <tr>
            <td>'.$row->stu_id.'</td>
              <td>'.$passport.'</td>
                   <td>'.$row->stud_fname. ' '.$row->stud_lname.'</td>
                     <td>'.$row->stud_regNo.'</td>
                       <td>'.$row->stud_dept.'</td>
                        <td>'.$row->stud_level.'</td>
                       <td>'.pretty_dates($row->stud_date_joined).'</td>
                       <td>'.timeAgo($row->stud_last_login).'</td>

                         <td>
                          <a href="#" id="'.$row->stu_id.'" title="Edit Student"  data-toggle="modal" data-target="#studentEditModal" class="text-info"><i class="fas fa-info-circle fa-lg"></i> </a>&nbsp;

                         <a href="#" id="'.$row->stu_id.'" title="View Details"  data-toggle="modal" data-target="#studentDetailModal" class="text-primary"><i class="fas fa-info-circle fa-lg"></i> </a>&nbsp;
                         
                         <a href="#" id="'.$row->stud_unique_id.'" title="View Details"  data-toggle="modal" data-target="#studentLogbookModal" class="text-warning"><i class="fas fa-info-circle fa-lg"></i> </a>&nbsp;

                         <a href="#" id="'.$row->stu_id.'" title="Trash Student" class="text-danger deleteStudentIcon"><i class="fa fa-recycle fa-lg"></i> </a>&nbsp;

                         </td>
          </tr>
          ';
    }



    $output .= '
      </tbody>
    </table>';
  }
  return  $output;

}else{
  echo '<h3 class="text-center text-secondary align-self-center lead">No Student   yet</h3>';
}

}

public function fetchNotifactionCount(){
  $sql = "SELECT * FROM students WHERE hod_approval = 0 OR circulation_approval = 0 OR approved = 0 ";
   $this->_db->query($sql);
  if ($this->_db->count()) {
    return $this->_db->count();
  }else{
    return false;
}
}


public function approveAction($field,$val, $user_id)
{
  $data = $this->_db->get('students', array('id', '=', $user_id));
  if ($data->count()) {
    $dat = $data->first();
    $userid = $dat->id;
    if ($dat->updated == 1) {
      $sql = "UPDATE students SET $field = 1, approved = $val WHERE id = '$user_id'";
      $query = $this->_db->query($sql);
      if ($query) {
        return true;
      }else{
        return false;

      }
    }else{
      $show = new Show();
      echo $show->showMessage('danger','The student or staff have not updated his/her details completely!', 'warning');
    }
  }

}

public function giveCard($user_id)
{
  $stamp = 'stamp.jpeg';
  $this->_db->insert('greenCards', array(
    'user_id' => $user_id,
    'stamp' => $stamp
  ));
  return true;
}

public function getStudentDetail($student_id)
  {
    $student = $this->_db->get('students', array('id', '=', $student_id));
    if ($student->count()) {
      return  $student->first();

    }else{
      return false;
    }
}

public function fetchOffenders(){
    $output = '';
    $imgPath = '../studentPortal/avaters/';


    $this->_db->get('offenders', array('pardoned', '=', 0));
    if ($this->_db->count()) {
      $dat = $this->_db->results();
    if ($dat) {
      $output .= '
      <table class="table table-striped table-hover" id="showOffender">
        <thead>
          <tr>
            <th>#</th>
            <th>Photo</th>
            <th>Full Name</th>
            <th>Matric/File No</th>
            <th>Offence</th>
            <th>Punishment</th>
            <th>Details</th>
            <th>Restore Access</th>


          </tr>
        </thead>
        <tbody>
      ';
      foreach ($dat as $row) {
        $student =  $this->_db->get('students', array('id', '=', $row->user_id));
        if ($student->count()) {
          $thatStudent = $student->first();
        }
          $passport = '<img src="'.$imgPath.  $thatStudent->passport.'"  alt="User Image" width="70px" height="70px" style="border-radius:50px;">';

          if ($thatStudent->permission == 'lib_staff') {
            $idno = $thatStudent->fileNo;
          }else{
            $idno = $thatStudent->matric_no;
          }

        $output .= '
            <tr>
              <td>'.$thatStudent->id.'</td>
                <td>'.$passport.'</td>
                     <td>'.$thatStudent->full_name.'</td>
                       <td>'.$idno.'</td>
                         <td>'.$row->offence.'</td>
                         <td>'.$row->punishment.'</td>

                           <td>
                           <a href="detail/student-detail/'.$thatStudent->id.'" id="'.$thatStudent->id.'" title="View Details" class="btn btn-primary btn-sm">Details</a>&nbsp;

                           </td>
                           <td>
                           <a href="#" id="'.$thatStudent->id.'" title="Trash Student" class="btn  btn-sm btn-danger deleteStudentIcon">Restore Access</a>&nbsp;

                           </td>
            </tr>
            ';
      }



      $output .= '
        </tbody>
      </table>';
    }
    return  $output;

  }else{
    echo '<h3 class="text-center text-secondary align-self-center lead">No Offender In database</h3>';
  }

}
//log in error
public function sendToLog($studentId)
{
    $this->_db->insert('offenders', array(
        'user_id'  => $studentId,
        'offence' => 'Failed to return book as at well due!',
        'punishment' => 'Banned from borrowing book from the library, with immediate effect!'
      ));
      return true;
}

public function updateOffended($studentId)
{
  $this->_db->update('students', 'id', $studentId, array(
    'offended' => 1
  ));
  return true;
}


public function updateTimeInBorrowed($studentId)
    {
        $this->_db->update('borrowed_books', 'user_id', $studentId, array(
            'time_before_log' => '00:00:00'
        ));
        return true;
    }

















   }//end of class
