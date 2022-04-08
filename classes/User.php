<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
/**
 * user class
 */
class User
{
  private  $_db,
           $_studentData,
           $_sessionName,
           $_cookieName,
           $_isLoggedIn;

public function __construct($user = null)
  {
    $this->_db = Database::getInstance();
    $this->_sessionName = Config::get('session/session_students');
    $this->_cookieName = Config::get('cookie/cookie_name');

    if (!$user) {
      if (Session::exists($this->_sessionName)) {
        $user = Session::get($this->_sessionName);

        if ($this->findUser($user)) {
          $this->_isLoggedIn = true;
        }else{
          //process logout
        }
      }
    }else{
     return  $this->findUser($user);
    }

  }

public function create($fields =  array())
{
    if (!$this->_db->insert('students', $fields)) {
      throw new Exception("Error Processing Request create account", 1);

    }
}
//find user details for login
public function findUser($user = null)
    {
      if ($user) {
       $field = (is_numeric($user)) ? 'stu_id' : 'stud_regNo';
       $data = $this->_db->get('students', array($field, '=', $user));
       if ($data->count()) {
         $this->_studentData = $data->first();
         return true;
       }
      }
      return false;
    }

//login
public function login($stud_regNo = null, $password = null)
{
    $show = new Show();
  $user = $this->findUser($stud_regNo);

  if ($user) {
      if ($this->data()->suspened == 0){
        $uniqueid = $this->data()->stud_unique_id;

        if (password_verify($password, $this->data()->stud_password)) {
           $ch = "SELECT * FROM secureOtp WHERE user_uniqueid = '$uniqueid'";
          $query = $this->_db->query($ch);
        if ($query->count()) {
          $sql2 = "UPDATE secureOtp SET secure_token = NULL WHERE user_uniqueid = '$uniqueid'";
         $this->_db->query($sql2);
        }else{
          // $sql56 = "INSERT INTO secureOtp (user_uniqueid) VALUES ('$uniqueid')";
          //  $this->_db->query($sql56);
           $this->_db->insert('secureOtp', array(
             'user_uniqueid' => $uniqueid
           ));
       }
        $rndno=rand(10000000, 99999999);//OTP generate
        $token = "TOKEN: "."<h2>".$rndno."</h2>";
         // Load Composer's autoloader
         require APPROOT . '/vendor/autoload.php';
         $fullname = $this->data()->stud_fname;
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
               $mail->addAddress($this->data()->stud_email);
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
        //  $date = date('M d, Y h:i A');
        //  $this->_db->update('adminOtp', 'admin_unique', $uniqueid, array(
        //  'secure_token' => $rndno,
        //  'status' => 'unused',
        //  'dateSent' => $date
        // ));
         $sql23 = "UPDATE secureOtp SET secure_token = '$rndno', status = 'unused', dateSent = NOW() WHERE user_uniqueid = '$uniqueid'";
          $this->_db->query($sql23);

          $email = $this->data()->stud_email;
         Session::put($this->_sessionName, $this->data()->stu_id);
         $sql = "UPDATE students SET stud_last_login = NOW() WHERE stud_email = '$email' ";
          $this->_db->query($sql);

         return true;

        } catch (\Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

       // Session::put($this->_sessionName, $this->data()->stu_id);
       // $email = $this->data()->stud_email;
       //  $sql = "UPDATE students SET stud_last_login = NOW() WHERE stud_email = '$email' ";
       //    $this->_db->query($sql);
       //    return true;
        }else{
          echo $show->showMessage('danger','Password Incorrect', 'warning');
          return false;
        }
      }else{
          echo $show->showMessage('danger','You have been suspended! please contact the Administrator', 'warning');
          return false;
      }
  }else{
      echo $show->showMessage('danger','Student not found', 'warning');
      return false;
  }



}


public function data()
{
  return $this->_studentData;
}


public function isLoggedIn(){
  return $this->_isLoggedIn;
}

public function logout()
{
  Session::delete($this->_sessionName);
}

public function createVerification($fields =  array())
{
    if (!$this->_db->insert('otp_table', $fields)) {
      throw new Exception("Error Processing Request email verify", 1);

    }
}
//find email
public function findEmail($email)
{
  $data = $this->_db->get('students', array('stud_email', '=', $email));
  if ($data->count()) {
    $this->_studentData = $data->first();
    return $this->_studentData;
      }else{
    return false;
  }

}

//find phone number
public function findPhone($phoneNo)
{
  $data = $this->_db->get('students', array('phone_number', '=', $phoneNo));
  if ($data->count()) {
     $this->_studentData = $data->first();
    return true;
  }else{
    return false;
  }

}
public function updateStudent($username, $email)
{
  // $this->_db->update('superusers', 'sudo_email', $email, array(
  //  'sudo_username' => $username
  // ));

  $sql = "UPDATE students SET username = '$username' WHERE email = '$email' ";
  $this->_db->query($sql);
  return true;
}
//find matric no
public function findMatricNo($matricNo)
{
  $data = $this->_db->get('students', array('matric_no', '=', $matricNo));
  if ($data->count()) {
     $this->_studentData = $data->first();
    return true;
  }else{
    return false;
  }

}


// find username
public function findUsername($username){
  $data = $this->_db->get('users', array('user_username', '=', $username));
  if ($data->count()) {
   $this->_studentData = $data->first();
    return true;
  }else{
    return false;
  }

}

public function updateProfile($profile, $userid)
    {
      $up = $this->_db->update('users','id', $userid, array(
        'profile_pic' => $profile
    ));
      if ($up) {
        return true;
      }else{
        return false;
      }
    }
//password reset
   // delete token
public function deleteToken($email, $field = array())
    {
      $this->_db->delete('pwdReset', array('email', '=', $email));
    }









public function updateRecoreds($user_id, $field = array())
{
	if(!$this->_db->update('students', 'id', $user_id, $field)){
         throw new Exception("Error Processing Request", 1);
         return false;

  }
}




public function updateStudentRecored($student_id, $field, $value)
{
	$this->_db->update('students', 'id', $student_id, array(
    	$field => $value

    ));

    return true;
}

public function change_password($id, $hashNewPass)
{
	$this->_db->update('students', 'stu_id', $id, array(
    	'stud_password' => $hashNewPass,

    ));

    return true;


}


public function deleteVkey($id){
	if($this->_db->delete('verifyEmail', array('user_id', '=', $id))){
		  return true;

		}else{
			return false;
		}
}

public function updateVkey($token, $id){
	$this->_db->insert('verifyEmail', array(
		'token' => $token,
		'user_id' => $id
	));
	return true;

}

public function updateProfileDelete($uid){
	$this->_db->update('userprofile', 'user_id', $uid, array(
		'status' => 1
	));
  return true;
}
public function getGreenCard($id)
{
  $student = $this->_db->get('greenCards', array('user_id', '=', $id));
  if ($student->count()) {
    return  $student->first();
  }else{
    return false;
  }
}



public function selectToken($token, $userid){

  $sql = "SELECT * FROM verifyEmail WHERE token = '$token' AND user_id = '$userid'";
 $this->_db->query($sql);
 if ($this->_db->count()) {
 	return $this->_db->first();
 }else{
 	return false;
 }
}

public function verify_email($uniqueid){
	$this->_db->update('students', 'stud_unique_id', $uniqueid, array(
		'verified' => 1
	));
  return true;
}

public function selectSelector($selector){

  $sql = "SELECT * FROM pwdReset WHERE pwdResetSelector = '$selector' AND pwdResetExpires > NOW()";
  $this->_db->query($sql);
 if ($this->_db->count()) {
   return $this->_db->first();
 }else{
  return false;
 }
}

// public function selectUser($email){
//   $sql = "SELECT * FROM users WHERE email = ? AND deleted = 0";
//   $stmt = $this->_pdo->prepare($sql);
//   $stmt->execute([$email]);
//   $user = $stmt->fetch(PDO::FETCH_OBJ);
// return $user;
// }


public function updateUser($password,$email){
  $this->_db->update('users', 'email', $email, array(
    'password' => $password
  ));
  return true;
}

public function updateHits()
{
  $id = 0;
  $hits = $hits+1;
  $this->_db->update('visitors', 'id', $id, array(
    'hits' => $hits
  ));
  return true;

}


public function subNews($email){
  $this->_db->insert('update_subscribers', array(
    'user_email' => $email
  ));
  return true;
}


public function getUser($cu)
{
  $this->_db->get('users', array('id', '=', $cu));
  if ($this->_db->count()) {
    return $this->_db->first();
  }else{
    return false;
  }
}

public function activity($id){
    $sql = "UPDATE students SET last_login = NOW() WHERE id = '$id'";
    $d = $this->_db->query($sql);
    return true;
}

public function checkUploaded($user_id, $week)

{
  $sql = "SELECT * FROM logbookOthers WHERE stu_unqiue_id = '$user_id' AND week_number = '$week' AND uploaded = 0 ";
  $df = $this->_db->query($sql);
if ($df->count()) {
   return true;
  }else{
    return false;
  }

}


//end of class
}
