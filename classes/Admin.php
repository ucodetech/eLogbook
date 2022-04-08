<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
/**
 * Admin class
 */

class Admin
{
		private $_db,
				$_data,
				$_sessionName,
				$_cookieName,
				$_isLoggedIn;

	function __construct($admin = null)
	{
		$this->_db =  Database::getInstance();
		$this->_sessionName = Config::get('session/session_admin');
		$this->_cookieName = Config::get('remember/cookie_name');


		if (!$admin) {
			if (Session::exists($this->_sessionName)) {
				$admin = Session::get($this->_sessionName);
				if ($this->findAdmin($admin)) {
					$this->_isLoggedIn = true;
				}

			}
		}else{
			$this->findAdmin($admin);
		}

	}



public function findAdmin($admin = null)
{
	if ($admin) {

	$field = (is_numeric($admin))? 'id' : 'admin_username';
	$data = $this->_db->get('admin', array($field, '=', $admin));
	if ($data->count()) {
		$this->_data = $data->first();
		return true;
	}
}
return false;
}

public function login($supername = null, $password = null)
{
    $show = new Show();
	$admin = $this->findAdmin($supername);
	if ($admin) {
		$adminPassword = $this->data()->admin_password;
		$adminEamil = $this->data()->admin_email;
		$adminId = $this->data()->id;
		$fullname = $this->data()->admin_fullname;
		$uniqueid = $this->data()->admin_uniqueid;
		if (password_verify($password, $adminPassword)) {
				$ch = "SELECT * FROM adminOtp WHERE admin_unique = '$uniqueid'";
				$query = $this->_db->query($ch);
				if ($query->count()) {
					$sql2 = "UPDATE adminOtp SET secure_token = Null WHERE admin_unique = '$uniqueid'";
	           $this->_db->query($sql2);
				}else{
					// $sql = "INSERT INTO adminOtp (admin_unique) VALUES ('$uniqueid')";
          //  $this->_db->query($sql);
					 $this->_db->insert('adminOtp', array(
						 'admin_unique' => $uniqueid
					 ));
				}

				$rndno=rand(10000000, 99999999);//OTP generate
				$token = "TOKEN: "."<h2>".$rndno."</h2>";
				 // Load Composer's autoloader
				 require APPROOT . '/vendor/autoload.php';
			    $mail =  new PHPMailer(true);
			 //
				  	try{

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
                $mail->addAddress($this->data()->admin_email);
               // $mail->addReplyTo("ucodetut@gmail.com", "Library Offence Doc.");
               $mail->Subject = 'Device Verification';
               $mail->Body = "
            <div style='width:80%; height:auto; padding:10px; margin:10px'>

        <p style='color: #fff; font-size: 20px; text-align: center; text-transform: uppercase;margin-top:0px'>One Time Password Verification<br></p>
        <p>Hey $fullname! <br><br>

        A sign in attempt requires further verification because we did not recognize your device. To complete the sign in, enter the verification code on the unrecognized device.

       <br><hr>
        $token <br><hr>

        If you did not attempt to sign in to your account, your password may be compromised. Contact Administrator to create a new, strong password for your ELB account.</p>
                <hr>

       </div>
        ";
        if($mail->send())
				// 	$date = date('M d, Y h:i A');
				// 	$this->_db->update('adminOtp', 'admin_unique', $uniqueid, array(
				// 	'secure_token' => $rndno,
				// 	'status' => 'unused',
				// 	'dateSent' => $date
				// ));
         $sql23 = "UPDATE adminOtp SET secure_token = '$rndno', status = 'unused', dateSent = NOW() WHERE admin_unique = '$uniqueid'";
          $this->_db->query($sql23);

					$email = $this->data()->admin_email;
         Session::put($this->_sessionName, $this->data()->id);
         $sql = "UPDATE admin SET admin_last_login = NOW() WHERE admin_email = '$email' ";
          $this->_db->query($sql);

         return true;

        } catch (\Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    	 // Session::put($this->_sessionName, $this->data()->id);
    	 //  $sql = "UPDATE admin SET admin_lastLogin = NOW() WHERE admin_email = '$adminEamil' ";
       //    $this->_db->query($sql);
	}else{
			echo $show->showMessage('danger','Incorrect Password Retype!', 'warning');
			return false;
		}


	}else{
        echo $show->showMessage('danger','Admin not found!', 'warning');
        return false;
	}
}
// check otp_veri
public function checkOtp()
{
	$this->_db->get('admin', array('admin_email', '=', $this->data()->admin_email));
	if ($this->_db->count()) {
		return $this->_db->first();
	}else{
		return false;
	}
}
    /**
     * @return bool
     */
    public function isIsLoggedIn()
    {
        return $this->_isLoggedIn;
    }

public function logout()
{
	Session::delete($this->_sessionName);

}

public function data()
{
	return $this->_data;
}


public function getAdminId()
{
 	return $this->data()->id;
}
public function create($field = array())
{
	if (!$this->_db->insert('admin', $field)) {
		throw new Exception("Error Processing Request", 1);

	}
}


public function findEmail($email)
{
	$check = $this->_db->get('admin', array('admin_email', '=', $email));
	if ($check->count()) {
		return $check->first();
	}else{
		return false;
	}

}

public function findPhone($phoneNo)
{
	$check = $this->_db->get('admin', array('admin_phoneNo', '=', $phoneNo));
	if ($check->count()) {
	 return $check->first();
	}else{
		return false;
	}

}

public function findFileNo($fileNo)
{
	$check = $this->_db->get('admin', array('admin_fileNo', '=', $fileNo));
	if ($check->count()) {
		return $check->first();
	}else{
		return false;
	}

}

public function updateAdmin($username, $email)
{
	// $this->_db->update('admin', 'admin_email', $email, array(
	// 	'admin_username' => $username
	// ));

	$sql = "UPDATE admin SET admin_username = '$username' WHERE admin_email = '$email' ";
	$this->_db->query($sql);
	return true;
}

public function updateAdminLog($id)
    {

        $sql = "UPDATE admin SET admin_last_login = NOW() WHERE id = '$id' ";
        $this->_db->query($sql);
        return true;
    }

public function insertProfile($adminId)
{
	$this->_db->insert('super_profile', array(
		'admin_id' => $adminId,
		'status' => '1'
	));
	return true;
}

    public function updateAdminRecored($admin_id, $field = array())
    {
        $this->_db->update('admin', 'id', $admin_id, $field);
        return true;
    }

    public function loggedAdmin(){
        $sql = "SELECT * FROM admin WHERE admin_last_login > DATE_SUB(NOW(), INTERVAL 5 SECOND)";
        $data = $this->_db->query($sql);
        if ($data->count()) {
            return $data->results();
        }else{
            return false;
        }
    }


public function change_password($admin_id, $field = array())
{
  if (!$this->_db->update('admin', 'id', $admin_id, $field)) {
  	throw new Exception("Error changing password!", 1);

  }
}

// fetch admins
public function grabAdmin()
{
	$check = $this->_db->get('admin', array('deleted', '=', 0));
	if ($check->count()) {
		$data =  $check->results();
		$output = '';
		$output .='<table class="table table-hover" id="showAdmin">
				<thead class="text-warning">
					<th>#</th>
					<th>Name</th>
					<th>Username</th>
					<th>Last Login</th>
					<th>Spv Status</th>
					<th>Action</th>

				</thead>
				<tbody>
					';
					$x = 0;
		foreach ($data as $ad) {
			$x = $x+1;
			$output .='
			<tr>
				<td>'.$x.'</td>
				<td>'.$ad->admin_fullname.'</td>
				<td>'.$ad->admin_username.'</td>
				<td>'.pretty_dates($ad->admin_last_login).'</td>
				<td>
					<a href="#" u-id="'.$ad->admin_uniqueid.'" data-toggle="modal" data-target="#adminSuperStatus" class="text-primary adminSuperStatusIcon" title="Admin Supervision Status"><i class="fa fa-toggle-on fa-lg"></i></a>&nbsp;
				</td>
				<td>
				<a href="#" id="'.$ad->id.'" data-toggle="modal" data-target="#adminDetail" class="text-primary adminDetailIcon" title="Admin Detail"><i class="fa fa-info-circle fa-lg"></i></a>&nbsp;
				<a href="#" id="'.$ad->id.'" data-toggle="modal" data-target="#adminEdit" class="text-success  adminEditIcon" title="Edit Admin"><i class="fa fa-edit fa-lg"></i></a>&nbsp;
				<a href="#" id="'.$ad->id.'" class="text-danger  adminSuspendIcon" title="Suspend Admin"><i class="fa fa-stop fa-lg"></i></a>&nbsp;
				</td>

			</tr>
			';

		}
		$output .='
						</tbody>
					</table>';

	return $output;


	}else{
		return "<h3 class='text-danger text-lg'>No data yet</h3>";
	}
}


public function updateAdminVerified($id, $fields = array())
{

$this->_db->update('admin', 'id', $id, $fields);
return true;
}

public function updateAdminOtp($uniqueid, $fields = array())
{

$this->_db->update('adminOtp', 'admin_unique', $uniqueid, $fields);
return true;

}


public function grabAdminData($uniqueid)
{
	$data = $this->_db->get('admin', array('admin_uniqueid', '=', $uniqueid));
	if ($data->count()) {
		return $data->first();

	}else{
		return false;
	}
}

public function getAnyTableFirst($table,$value, $key)
{
	$data = $this->_db->get($table, array($value, '=', $key));
	if ($data->count()) {
		return $data->first();

	}else{
		return false;
	}
}

public function getAnyTableAll($table,$value, $key)
{
	$data = $this->_db->get($table, array($value, '=', $key));
	if ($data->count()) {
		return $data->results();

	}else{
		return false;
	}
}

// insert activation
public function activateSupervision($fields = array())
{
	if (!$this->_db->insert('supervisors', $fields)) {
		throw new Exception("Error Processing supervision status", 1);

	}
}

// fetch admins
public function grabSupervisors()
{
	$check = $this->_db->get('supervisors', array('deleted', '=', 0));
	if ($check->count()) {
		$data =  $check->results();
		$output = '';
		$output .='<table class="table table-hover" id="showSupervisors">
				<thead class="text-warning">
					<th>#</th>
					<th>Name</th>
					<th>Department</th>
					<th>Email</th>
					<th>City</th>
					<th>Assigned Students</th>
					<th>No of Students</th>
					<th>Assign Students</th>
					<th>Check Student Assigned</th>

				</thead>
				<tbody>
					';
					$x = 0;
		foreach ($data as $ad) {
			$x = $x+1;
			$supervisor = new Admin($ad->session_id);
			$fullname = $supervisor->data()->admin_fullname;
			$email = $supervisor->data()->admin_email;
			$uniqueid = $supervisor->data()->admin_uniqueid;
			$id = $supervisor->data()->id;

			if (($ad->assigned_to_students) == 1) {
				$no ='<span class="text-success">Yes</span>';
			}else{
				$no ='<span class="text-danger">No</span>';
			}

			$output .='
			<tr>
				<td>'.$x.'</td>
				<td>'.$fullname.'</td>
				<td>'.$ad->department.'</td>
				<td>'.$email.'</td>
				<td>'.$ad->city.'</td>
				<td>'.$no.'</td>
				<td>'.$ad->no_of_students_assigned_to.'</td>
				<td>
					<a href="#" super-id="'.$uniqueid.'" city-id="'.$ad->city.'" data-toggle="modal" data-target="#assignStudents" class="text-primary assignStudentsIcon" title="Assign Students"><i class="fa fa-toggle-on fa-lg"></i></a>&nbsp;
				</td>
				<td>
					<a href="#" u-id="'.$ad->id.'" data-toggle="modal" data-target="#studentUnderMe" class="text-primary  studentUnderMeIcon" title="Students"><i class="fa fa-info-circle fa-lg"></i></a>&nbsp;
				</td>

			</tr>
			';

		}
		$output .='
						</tbody>
					</table>';

	return $output;


	}else{
		return "<h3 class='text-danger text-lg'>No data yet</h3>";
	}
}


// check all table
public function checkAnyTable($table, $field, $key)
{
	$data = $this->_db->get($table, array($field, '=', $key));
	if ($data->count()) {
		return true;
	}else{
		return false;
	}
}
// fetch admins
public function grabPlacement()
{
	$check = $this->_db->get('placementInfo', array('deleted', '=', 0));
	if ($check->count()) {
		$data =  $check->results();
		$output = '';
		$output .='<table class="table table-hover"  id="showPlacements">
				<thead class="text-warning">
					<th>#</th>
					<th>Assigned Status</th>
					<th>Student Unique</th>
					<th>Name of Estab</th>
					<th>Location</th>
					<th>City</th>
				</thead>
				<tbody>
					';
					$x = 0;
		foreach ($data as $ad) {
			$x = $x+1;

			$output .='
			<tr>
				<td>'.$x.'</td>
				<th>'.$ad->assigned.'</th>
				<td>'.$ad->stud_unique_id.'</td>
				<td>'.$ad->nameOfEst.'</td>
				<td>'.$ad->location.'</td>
				<td>'.$ad->city.'</td>

			</tr>
			';

		}
		$output .='
						</tbody>
					</table>';

	return $output;


	}else{
		return "<h3 class='text-danger text-lg'>No data yet</h3>";
	}
}



// check all table
public function checkStudent($yes, $userid)
{
	$data = $this->_db->query("SELECT * FROM placementInfo WHERE assigned = '$yes' AND  stud_unique_id = '$userid' ");
	if ($data->count()) {
		return true;
	}else{
		return false;
	}
}

// check all table
public function checkStudentForInd($induseridstudent)
{
	$data = $this->_db->query("SELECT * FROM students WHERE stud_unique_id = '$induseridstudent' AND ind_supervisor_unid != 'NULL' ");
	if ($data->count()) {
		return true;
	}else{
		return false;
	}
}

public function grabStudentsUnderMe($superid)
{
	$sql = "SELECT * FROM students WHERE assigned_supervisor_unid = '$superid'";
	$data = $this->_db->query($sql);
	if ($data->count()) {
		$dta =  $data->results();
		$output ='';

    $output .= '<table class="table table-condensed table-striped table-hover" id="ShowstudentsUnderme">
  <thead>
    <th>#</th>
    <th>Passport</th>
    <th>Fullname</th>
    <th>Phone No</th>
    <th>Matric No.</th>
    <th>Department</th>
    <th>School</th>
  </thead>
  <tbody>

  ';
  foreach ($dta as $stuME) {
  	$photo = '<img src="../students/profile/'.$stuME->passport.'" alt="'.$stuME->stud_fname.'" class="img-rounded profileSF" width="120">';

   $output .= '
   <tr>
      <td>'.$stuME->stu_id.'</td>
      <td>'.$photo.'</td>
      <td>'.$stuME->stud_fname. ' '.$stuME->stud_lname.' '.$stuME->stud_oname.'</td>
      <td>'.$stuME->stud_tel.'</td>
      <td>'.$stuME->stud_regNo.'</td>
      <td>'.$stuME->stud_dept.'</td>
      <td>'.$stuME->stud_school.'</td>
    </tr>';
  }


$output .='
 </tbody>
</table>';
return $output;


	}else{
		return 'No Student Assigned to you yet!';
	}
}

public function grabStudentsUnderMe2($superid)
{
	$sql = "SELECT * FROM students WHERE assigned_supervisor_unid = '$superid'";
	$data = $this->_db->query($sql);
	if ($data->count()) {
		return   $data->results();

	}else{
		return 'No Student Assigned to You yet!';
	}
}

public function getLogOthers($uniqueid, $week_number)
{
	 $sql = "SELECT * FROM logbookOthers WHERE stu_unique_id = '$uniqueid' AND week_number = '$week_number' ";
	$data = $this->_db->query($sql);
	if ($data->count()) {
		return   $data->first();

	}else{
		return 'No Student';
	}
}



}//end of class
