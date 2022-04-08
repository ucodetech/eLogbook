<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
/**
 * Supervisor class
 */

class Supervisor
{
		private $_db,
				$_superData,
				$_sessionName,
				$_cookieName,
				$_isLoggedIn;

	function __construct($Supervisor = null)
	{
		$this->_db =  Database::getInstance();
		$this->_sessionName = Config::get('session/session_supervisor');
		$this->_cookieName = Config::get('remember/cookie_name');


		if (!$Supervisor) {
			if (Session::exists($this->_sessionName)) {
				$Supervisor = Session::get($this->_sessionName);
				if ($this->findSupervisor($Supervisor)) {
					$this->_isLoggedIn = true;
				}

			}
		}else{
			$this->findSupervisor($Supervisor);
		}

	}



public function findSupervisor($Supervisor = null)
{
	if ($Supervisor) {

	$field = (is_numeric($Supervisor))? 'id' : 'comp_email';
	$data = $this->_db->get('inds_supervisors', array($field, '=', $Supervisor));
	if ($data->count()) {
		$this->_superData = $data->first();
		return true;
	}
}
return false;
}

public function login($comp_email = null, $password = null)
{
    $show = new Show();
	$Supervisor = $this->findSupervisor($comp_email);
	if ($Supervisor) {
		$SupervisorPassword = $this->superdata()->password;
		$SupervisorEamil = $this->superdata()->comp_email;
		$SupervisorId = $this->superdata()->id;
		$fullname = $this->superdata()->fullname;
		$uniqueid = $this->superdata()->unique_id;
		if($this->superdata()->status === 'on'){
			if (password_verify($password, $SupervisorPassword)) {
					$ch = "SELECT * FROM secureOtp WHERE user_uniqueid = '$uniqueid'";
					$query = $this->_db->query($ch);
					if ($query->count()) {
						$sql2 = "UPDATE secureOtp SET secure_token = Null WHERE user_uniqueid = '$uniqueid'";
		           $this->_db->query($sql2);
					}else{
						// $sql = "INSERT INTO SupervisorOtp (Supervisor_unique) VALUES ('$uniqueid')";
	          //  $this->_db->query($sql);
						 $this->_db->insert('secureOtp', array(
							 'user_uniqueid' => $uniqueid
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
							$mail->Username = "ucodetech.wordpress@gmail.com";
							$mail->Password =  "hash.algorthim.super()@#***";
							$mail->SMTPSecure = "ssl";
							$mail->Port = 465; // for tls

							 //email settings
							 $mail->isHTML(true);
							 $mail->setFrom("ucodetech.wordpress@gmail.com", "E-Log Book portal");
	               $mail->addAddress($this->superdata()->comp_email);
	               // $mail->addReplyTo("ucodetut@gmail.com", "Library Offence Doc.");
	               $mail->Subject = 'Device Verification';
	               $mail->Body = "
	            <div style='width:80%; height:auto; padding:10px; margin:10px'>

	        <p style='color: #fff; font-size: 20px; text-align: center; text-transform: uppercase;margin-top:0px'>One Time Password Verification<br></p>
	        <p>Hey $fullname! <br><br>

	        A sign in attempt requires further verification because we did not recognize your device. To complete the sign in, enter the verification code on the unrecognized device.

	       <br><hr>
	        $token <br><hr>

	        If you did not attempt to sign in to your account, your password may be compromised. Contact Supervisoristrator to create a new, strong password for your ELB account.</p>
	                <hr>

	       </div>
	        ";
	        if($mail->send())
					// 	$date = date('M d, Y h:i A');
					// 	$this->_db->update('SupervisorOtp', 'Supervisor_unique', $uniqueid, array(
					// 	'secure_token' => $rndno,
					// 	'status' => 'unused',
					// 	'dateSent' => $date
					// ));
		         $sql23 = "UPDATE secureOtp SET secure_token = '$rndno', status = 'unused', dateSent = NOW() WHERE user_uniqueid = '$uniqueid'";
		          $this->_db->query($sql23);

				 $email = $this->superdata()->comp_email;
		         Session::put($this->_sessionName, $this->superdata()->id);
		         $sql = "UPDATE inds_supervisors SET last_login = NOW() WHERE comp_email = '$email' ";
		          $this->_db->query($sql);

		         return true;

	        } catch (\Exception $e) {
	        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	        }
	    	 // Session::put($this->_sessionName, $this->superdata()->id);
	    	 //  $sql = "UPDATE Supervisor SET Supervisor_lastLogin = NOW() WHERE comp_email = '$SupervisorEamil' ";
	       //    $this->_db->query($sql);
			}else{
				echo $show->showMessage('danger','Incorrect Password Retype!', 'warning');
				return false;
				}
		}else{
			echo $show->showMessage('danger','Contact System Administrator!', 'warning');
			return false;
		}

	}else{
        echo $show->showMessage('danger','Supervisor not found!', 'warning');
        return false;
	}
}
// check otp_veri
public function checkOtp()
{
	$this->_db->get('inds_supervisors', array('comp_email', '=', $this->superdata()->comp_email));
	if ($this->_db->count()) {
		return $this->_db->first();
	}else{
		return false;
	}
}
    /**
     * @return bool
     */
    public function superIsLoggedIn()
    {
        return $this->_isLoggedIn;
    }

public function logout()
{
	Session::delete($this->_sessionName);

}

public function superdata()
{
	return $this->_superData;
}


public function getSupervisorId()
{
 	return $this->superdata()->id;
}
public function create($field = array())
{
	if (!$this->_db->insert('inds_supervisors', $field)) {
		throw new Exception("Error Processing Request", 1);

	}
}


public function findEmail($email)
{
	$check = $this->_db->get('inds_supervisors', array('comp_email', '=', $email));
	if ($check->count()) {
		return $check->first();
	}else{
		return false;
	}

}

public function findPhone($phoneNo)
{
	$check = $this->_db->get('inds_supervisors', array('phoneNo', '=', $phoneNo));
	if ($check->count()) {
	 return $check->first();
	}else{
		return false;
	}

}





public function updateSupervisorLog($id)
    {

        $sql = "UPDATE inds_supervisors SET last_login = NOW() WHERE id = '$id' ";
        $this->_db->query($sql);
        return true;
    }



public function updateSupervisorRecored($Supervisor_id, $field, $value)
{
    $this->_db->update('inds_supervisors', 'id', $Supervisor_id, array(
        $field => $value

    ));

    return true;
}
    public function loggedSupervisor(){
        $sql = "SELECT * FROM inds_supervisors WHERE last_login > DATE_SUB(NOW(), INTERVAL 5 SECOND)";
        $data = $this->_db->query($sql);
        if ($data->count()) {
            return $data->results();
        }else{
            return false;
        }
    }


public function change_password($hashNewPass, $Supervisor_id)
{
  $this->_db->update('inds_supervisors', 'unique_id', $Supervisor_id, array(
            'password' => $hashNewPass

        ));

        return true;
}



public function updateSupervisorVerified($id, $fields = array())
{

$this->_db->update('inds_supervisors', 'id', $id, $fields);
return true;
}

public function updateSupervisorOtp($uniqueid, $fields = array())
{

$this->_db->update('SupervisorOtp', 'Supervisor_unique', $uniqueid, $fields);
return true;

}
// fetch admins
public function grabSupervisors()
{
	$check = $this->_db->get('inds_supervisors', array('deleted', '=', 0));
	if ($check->count()) {
		$data =  $check->results();
		$output = '';
		$output .='<table class="table table-hover" id="showSupervisor">
				<thead class="text-warning">
					<th>#</th>
					<th>Name</th>
					<th>Coy Email</th>
					<th>Coy Name</th>
					<th>Coy Location</th>
					<th>Assign Students</th>
					<th>Check Student Assigned</th>

				</thead>
				<tbody>
					';
					$x = 0;
		foreach ($data as $ad) {
			$x = $x+1;


			$output .='
			<tr>
				<td>'.$x.'</td>
				<td>'.$ad->fullname.'</td>
				<td>'.$ad->comp_email.'</td>
				<td>'.$ad->company.'</td>
				<td>'.$ad->company_location.'</td>
				<td>
					<a href="#" inds-id="'.$ad->unique_id.'" email-id="'.$ad->comp_email.'" data-toggle="modal" data-target="#assignStudentsInd" class="text-primary assignStudentsIndIcon" title="Assign Students"><i class="fa fa-toggle-on fa-lg"></i></a>&nbsp;
				</td>
				<td>
					<a href="#" u-id="'.$ad->comp_email.'" data-toggle="modal" data-target="#studentUnderMeInd" class="text-primary  studentUnderMeIndIcon" title="Students"><i class="fa fa-info-circle fa-lg"></i></a>&nbsp;
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


public function getAnyTableAll($table,$value, $key)
{
	$data = $this->_db->get($table, array($value, '=', $key));
	if ($data->count()) {
		return $data->results();

	}else{
		return false;
	}
}
public function grabStudentsUnderMe2($superid)
{
	$sql = "SELECT * FROM students WHERE ind_supervisor_unid = '$superid'";
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
