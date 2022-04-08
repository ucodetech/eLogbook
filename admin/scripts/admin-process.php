<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../../core/init.php';
$admin = new Admin();
$show = new Show();
$validate = new Validate();
$db  = Database::getInstance();

if (isset($_POST['action']) && $_POST['action'] == 'addAdmin') {
  if (Input::exists()) {
    $validation = $validate->check($_POST, array(
      'fullname' => array(
        'required' => true,
        'min' => 5,
        'max' => 100
      ),
      'admin_phone_no' => array(
        'required' => true,
        'min' => 11,
        'max' => 15,
        'unique' => 'admin'
      ),
      'admin_email' => array(
        'required' => true,
        'unique' => 'admin'
      ),
      'password' => array(
        'required' => true,
        'min' => 10
      ),
      'comfirm_password' => array(
        'required' => true,
        'matches' => 'password'
      ),
      'department' => array(
        'required' => true
      ),
      'permission' => array(
        'required' => true
      )

    ));
    if ($validation->passed()) {
      $email = Input::get('admin_email');
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo $show->showMessage('danger', 'Invalid Email', 'warning');
        return false;
      }

      $password = Input::get('password');
      $fullname = Input::get('fullname');
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $rand = rand(10000000, 99999999);
      $rand2 = rand(1000, 9999);

      $unid = 'elog-'.$rand;
      $name = explode(' ', $fullname);
      $fname = $name[0];
      if (strlen($fname) > 10) {
        $userna = $fname[0].$fname[1].$fname[2].$fname[3].$fname[4];
      }else{
          $userna = $fname;
      }
      $username = $userna.$rand2;
      $status = "on";
      try {
        $admin->create(array(
              'admin_fullname' => Input::get('fullname'),
              'admin_phone_no' => Input::get('admin_phone_no'),
              'admin_email' => Input::get('admin_email'),
              'admin_department' => Input::get('department'),
              'admin_uniqueid' => $unid,
              'admin_username' => $username,
              'admin_password' => $hash,
              'admin_permissions' => Input::get('permission'),
              'admin_status' => $status
        ));




                $rndno=rand(10000000, 99999999);//OTP generate
                $token = "TOKEN: "."<h2>".$rndno."</h2>";
                $tel = Input::get('admin_phone_no');
                // _____________________________________________________

                // ---------------------------------------------------------
                // Load Composer's autoloader
          require APPROOT. '/vendor/autoload.php';
          $mail =  new PHPMailer(true);

            try{

             
               // $mail->SMTPDebug = 3;     //Enable verbose debug output
              $mail->isSMTP();
              $mail->Host = "smtp.gmail.com";
              $mail->SMTPAuth = true;
              $mail->Username = "yours@gmail.com";
              $mail->Password =  "yourpassword";
              $mail->SMTPSecure = "ssl";
              $mail->Port = 465; // for tls

               //email settings
               $mail->isHTML(true);
               $mail->setFrom("youremail", "E-Log Book portal");
               $mail->addAddress($email);
               // $mail->addReplyTo("ucodetut@gmail.com", "Library Offence Doc.");
               $mail->Subject = 'Device Verification';
               $mail->Body = "
                <div style='width:80%; height:auto; padding:10px; margin:10px'>

            <p style='color: #fff; font-size: 20px; text-align: center; text-transform: uppercase;margin-top:0px'>One Time Password Verification<br></p>
            <p>Hey $fullname! <br><br>

            Your registration was successfull. please enter the code blow on ur token page to verify your email.

           <br><hr>
            $token
            <hr>
            <p> Login Details </p>
            <span> Username: $username</span>
            <span> Password: $password</span>

            Note! You are to change your password immediately your first login

          </p>
           </div>
            ";
            if($mail->send())

             $sql = "INSERT INTO verifyAdmin (sudo_email, token) VALUES ('$email','$rndno')";
              Database::getInstance()->query($sql);
              echo 'success';

        } catch (\Exception $e) {
            echo $show->showMessage('danger', 'Message could not be sent. Mailer Error:' .$mail->ErrorInfo, 'warning');
            return false;
          }

      } catch (\Exception $e) {
        echo $show->showMessage('danger', $e->getMessage(), 'warning');
        return false;
      }


    }else{
      foreach ($validation->errors() as $error) {
        echo $show->showMessage('danger', $error, 'warning');
        return false;
      }
    }
  }

}
//login

if (isset($_POST['action']) && $_POST['action']== 'loginAdmin') {


  if (Input::exists()) {
    $validation = $validate->check($_POST, array(
      'admin_username' => array(
        'required' => true
      ),
      'password' => array(
        'required' => true
      )
    ));

    if ($validation->passed()) {
        $username = Input::get('admin_username');
        $password = Input::get('password');

        $login = $admin->login($username, $password);
        if ($login) {
          echo 'success';
        }

    }else{
      foreach ($validation->errors() as $error) {
        echo $show->showMessage('danger', $error, 'warning');
        return false;
      }
    }
  }
}

// fetch admins
if (isset($_POST['action']) && $_POST['action']== 'fatchAdmins') {
  $data = $admin->grabAdmin();
  if ($data) {
    echo $data;
  }
}

if (isset($_POST['uniquid']) && !empty($_POST['uniquid'])) {
  $uniqueid = $_POST['uniquid'];
   $data = $admin->grabAdminData($uniqueid);
  if ($data) {
    $output = '';
    $output .= '<form action="#" method="POST" id="updateSupervisionStatus">
  <div class="row text-primary">
    <input type="hidden" name="adminUniID" id="adminUniID" class="form-control text-dark" value="'.$data->admin_uniqueid.'">
     <input type="hidden" name="session_id" id="session_id" class="form-control text-dark" value="'.$data->id.'">
    <div class="form-group col-lg-12">
      <label for="superVuniqueid" class="text-primary">User Unique_id</label>
      <input type="text" name="superVuniqueid" id="superVuniqueid" class="form-control text-dark" value="'.$data->admin_uniqueid.'" readonly>
    </div>
    <div class="form-group col-lg-12">
      <label for="superVdepartment" class="text-primary">Department</label>
      <input type="text" name="superVdepartment" id="superVdepartment" class="form-control text-dark" value="'.$data->admin_department.'" readonly>
    </div>
    <div class="form-group col-lg-12">
      <label for="city" class="text-primary">City to Go</label>
      <input type="text" name="city" id="city" class="form-control text-dark">
    </div>
    <div class="form-group col-lg-12">
    <button class="btn btn-block btn-primary activateBtn" id="activateBtn" type="button">Activate</button>
    </div>
  </div>
</form>';
    echo $output;
  }
}
// perform activation
if (isset($_POST['action']) && $_POST['action']== 'updateStatusSupervision') {


    $adminUniID = $_POST['adminUniID'];
    $superVuniqueid = $_POST['superVuniqueid'];
    $superVdepartment = $_POST['superVdepartment'];
    $session_id = $_POST['session_id'];
    $superVLocation = $show->test_input($_POST['city']);
    $superVdepartment = strtoupper($superVdepartment);


    // check if supervisor have been added
    $check = $admin->getAnyTableFirst('supervisors','unique_id', $adminUniID);
    if ($check) {
      echo 'Supervisor activated already';
      return false;
    }
    if (empty($_POST['city'])) {
      echo 'Location can not be empty';
      return false;
    }
    try {

        $admin->activateSupervision(array(
          'session_id' => $session_id,
          'unique_id' => $adminUniID,
          'city' => $superVLocation,
          'department' => $superVdepartment
        ));

        echo 'supervision activated';

    } catch (Exception $e) {
      echo $show->showMessage('danger', $e->getMessage(), 'warning');
      return false;
    }

}


// fetch placement
if (isset($_POST['action']) && $_POST['action']== 'fatchPlacement') {
  $data = $admin->grabPlacement();
  if ($data) {
    echo $data;
  }
}



// fetch supervisors
if (isset($_POST['action']) && $_POST['action']== 'fatchSupervisors') {
  $data = $admin->grabSupervisors();
  if ($data) {
    echo $data;
  }
}


// fetch supervisors
if (isset($_POST['city_id']) && !empty($_POST['city_id'])) {
  $cityid = $_POST['city_id'];
  $sup_id = $_POST['sup_id'];
  $data = $admin->getAnyTableAll('placementInfo','city', $cityid);
  if ($data) {
    $output = '';

    $output .= '<table class="table table-condensed table-striped table-hover">
  <thead>
    <th>#</th>
    <th>City</th>
    <th>Fullname</th>
    <th>Phone No</th>
    <th>Matric No.</th>
    <th>Department</th>
    <th>School</th>
    <th>Assign</th>
  </thead>
  <tbody>

  ';
  foreach ($data as $placeForm) {
    $student = "SELECT * FROM students WHERE stud_unique_id = '$placeForm->stud_unique_id'";
    $gt = Database::getInstance()->query($student);
    $get = $gt->first();

   $output .= '
   <tr>
      <td>'.$placeForm->id.'</td>
      <td>'.$placeForm->city.'</td>
      <td>'.$get->stud_fname. ' '.$get->stud_lname.' '.$get->stud_oname.'</td>
      <td>'.$get->stud_tel.'</td>
      <td>'.$get->stud_regNo.'</td>
      <td>'.$get->stud_dept.'</td>
      <td>'.$get->stud_school.'</td>
      <td>
       <a href="#" s-id="'.$sup_id.'"  u-id="'.$get->stud_unique_id.'" class="btn btn-primary assignStudent" id="assignStudent">Assign </a>
      </td>
    </tr>';
  }

$output .='
 </tbody>
</table>';
echo $output;
  }
}


// assign students
if (isset($_POST['userid']) && !empty($_POST['userid'])) {
      $userid = $_POST['userid'];
      $superid = $_POST['superid'];
      $yes = 'yes';
      if ($admin->checkStudent($yes, $userid))
        {
        echo $show->showMessage('danger', 'Student assigned to supervisor already', 'warning');
        return false;
      }

        // update student and sent the supervisor unique_id
        $updateStudent = "UPDATE students SET assigned_supervisor_unid = '$superid'WHERE stud_unique_id = '$userid' ";
        $db->query($updateStudent);

        // update placementInfo set assigned to yes
        $updatePlacement = "UPDATE placementInfo SET assigned = 'yes' WHERE stud_unique_id = '$userid' ";
        $db->query($updatePlacement);

        // update supervisors set assigned to students = 1 and number of students assigned
        $updateSupervisor = "UPDATE supervisors SET assigned_to_students = 1, no_of_students_assigned_to = no_of_students_assigned_to + 1 WHERE unique_id = '$superid' ";
        $db->query($updateSupervisor);

        echo $show->showMessage('info', 'Student assigned to supervisor', 'check');
        return true;


    }


// fetch students under me
if (isset($_POST['action']) && $_POST['action']== 'fetchStudentsUnderMe') {
  $superid = $admin->data()->admin_uniqueid;
  $data = $admin->grabStudentsUnderMe($superid);
  if ($data) {
    echo $data;
  }
}

// search for student logbook
if (isset($_POST['action']) && $_POST['action']== 'searchLogbook') {
    $unique_id = $_POST['unique_id'];


    if (empty($_POST['unique_id'])) {
      echo $show->showMessage('danger', 'Please select the student', 'warning');
        return false;
    }

    if ($_POST['search_date'] && !empty($_POST['search_week'])) {
        echo $show->showMessage('danger', 'Search by Full Date only! or By Week Number only ', 'warning');
        return false;
    }


    if (empty($_POST['search_date']) && empty($_POST['search_week'])) {
        echo $show->showMessage('danger', 'Search by Full Date! or By Week Number ', 'warning');
        return false;
    }

     $search_date = (($_POST['search_date'] != '')?$show->test_input($_POST['search_date']): '');
     $search_week = (($_POST['search_week'] != '')?$show->test_input($_POST['search_week']): '');



    //query the database according to user request
    if ($search_date) {
          // $search_date = $_POST['search_date'];
           $sql = "SELECT * FROM logbook WHERE stu_unique_id = '$unique_id' AND log_month = '$search_date' ";
          $data = Database::getInstance()->query($sql);
          if ($data->count()) {
                $row = $data->first();

             echo '<div class="row">
               <div class="col-lg-2 text-left"  style="border:2px solid grey;">
                 <strong class="text-center">
                  '.pretty_dayLetterd($row->log_month).'
                 </strong><br>
                 <span class="text-center">
                 '.pretty_dates($row->log_month).'
               </span>
               </div>
              <div class="col-lg-8 text-left"  style="border:2px solid grey;">
                <stong class="text-left">
                '.$row->activity.'</stong>
              </div>
              <div class="col-lg-2  text-right" style="border:2px solid grey;">
              <u>Week: '.$row->week_number.'</u>
              </div>
            </div>';
          }else{
            echo $show->showMessage('danger','No record yet (by date)','warning');
            return false;
          }

    }elseif($search_week){

    // if(isset($_POST['search_week'])) {
         // $search_week  = $_POST['search_week'];

         $sql = "SELECT * FROM logbook WHERE stu_unique_id = '$unique_id' AND week_number = '$search_week'";
         $week = Database::getInstance()->query($sql);
         if ($week->count()) {
           $row = $week->first();
            echo '<a href="logDetails/student-logbook.php?log='.$row->stu_unique_id.'&week='.$row->week_number.'">View by week</a>';
          }else{
            echo $show->showMessage('danger','No record yet (week)','warning');
            return false;
          }
      // }

    }
}




// fetch students in a specific organisation
if (isset($_POST['inds_id']) && !empty($_POST['inds_id'])) {
  $indsid = $_POST['inds_id'];
  $emailid = $_POST['email_id'];
  $data = $admin->getAnyTableAll('placementInfo','company_email', $emailid);
  if ($data) {
    $output = '';

    $output .= '<table class="table table-condensed table-striped table-hover">
  <thead>
    <th>#</th>
    <th>City</th>
    <th>Fullname</th>
    <th>Phone No</th>
    <th>Matric No.</th>
    <th>Department</th>
    <th>School</th>
    <th>Assign</th>
  </thead>
  <tbody>

  ';
  foreach ($data as $placeForm) {
    $student = "SELECT * FROM students WHERE stud_unique_id = '$placeForm->stud_unique_id'";
    $gt = Database::getInstance()->query($student);
    $get = $gt->first();

   $output .= '
   <tr>
      <td>'.$placeForm->id.'</td>
      <td>'.$placeForm->city.'</td>
      <td>'.$get->stud_fname. ' '.$get->stud_lname.' '.$get->stud_oname.'</td>
      <td>'.$get->stud_tel.'</td>
      <td>'.$get->stud_regNo.'</td>
      <td>'.$get->stud_dept.'</td>
      <td>'.$get->stud_school.'</td>
      <td>
       <a href="#" indsass-id="'.$indsid.'"  stud-id="'.$get->stud_unique_id.'" class="btn btn-info assignStudentInd" id="assignStudent">Assign</a>
      </td>
    </tr>';
  }


$output .='
 </tbody>
</table>';
echo $output;
  }
}

// assign students to industrial base supervisors
if (isset($_POST['induseridstudent']) && !empty($_POST['induseridstudent'])) {
      $induseridstudent = $_POST['induseridstudent'];
      $inds_id = $_POST['inds_ids'];

      if ($admin->checkStudentForInd($induseridstudent))
        {
        echo $show->showMessage('danger', 'Student assigned to industrial base supervisor already', 'warning');
        return false;
      }

        // update student and sent the supervisor unique_id
        $updateStudent = "UPDATE students SET ind_supervisor_unid = '$inds_id' WHERE stud_unique_id = '$induseridstudent' ";
        $db->query($updateStudent);
        // // update supervisors set assigned to students = 1 and number of students assigned
        // $updateSupervisor = "UPDATE supervisors SET assigned_to_students = 1, no_of_students_assigned_to = no_of_students_assigned_to + 1 WHERE unique_id = '$superid' ";
        // $db->query($updateSupervisor);
        echo $show->showMessage('success', 'Student assigned to supervisor', 'check');
        return true;


    }
