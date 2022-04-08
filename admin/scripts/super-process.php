<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../../core/init.php';
$supervisor = new Supervisor();
$show = new Show();
$validate = new Validate();
$admin = new Admin();

if (isset($_POST['action']) && $_POST['action'] == 'addSupervisor') {
  if (Input::exists()) {
    $validation = $validate->check($_POST, array(
      'fullname' => array(
        'required' => true,
        'min' => 5,
        'max' => 100
      ),
      'phoneNo' => array(
        'required' => true,
        'min' => 11,
        'max' => 15,
        'unique' => 'inds_supervisors'
      ),
      'comp_email' => array(
        'required' => true,
        'unique' => 'inds_supervisors'
      ),
      'password' => array(
        'required' => true,
        'min' => 10
      ),
      'comfirm_password' => array(
        'required' => true,
        'matches' => 'password'
      ),
      'company' => array(
        'required' => true,
        'min' => 10
      ),
      'company_location' => array(
        'required' => true,

      ),


    ));
    if ($validation->passed()) {
      $email = Input::get('comp_email');
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo $show->showMessage('danger', 'Invalid Email', 'warning');
        return false;
      }

      $password = Input::get('password');
      $fullname = Input::get('fullname');
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $rand = rand(10000000, 99999999);
      $rand2 = rand(1000, 9999);

      $unid = 'ibs-'.$rand;
      $status = "on";
      try {
        $supervisor->create(array(
              'fullname' => Input::get('fullname'),
              'phoneNo' => Input::get('phoneNo'),
              'comp_email' => Input::get('comp_email'),
              'unique_id' => $unid,
              'password' => $hash,
              'status' => $status,
              'company' => Input::get('company'),
              'company_location' => Input::get('company_location')
        ));




                $rndno=rand(10000000, 99999999);//OTP generate
                $token = "TOKEN: "."<h2>".$rndno."</h2>";
                // _____________________________________________________

                // ---------------------------------------------------------
                // Load Composer's autoloader
          require APPROOT. '/vendor/autoload.php';
          $mail =  new PHPMailer(true);

            try{

               // //SMTP settings
               // $mail->isSMTP();
               // $mail->Host = "mail.ucodetuts.com.ng";
               // $mail->SMTPAuth = true;
               // $mail->Username = "noreply@ucodetuts.com.ng";
               // $mail->Password =  "warmechine500@#**@@";
               // $mail->SMTPSecure = "ssl";
               // $mail->Port = 465; //587 for tls
               // $mail->SMTPDebug = 3;     //Enable verbose debug output
              $mail->isSMTP();
              $mail->Host = "smtp.gmail.com";
              $mail->SMTPAuth = true;
              $mail->Username = "ucodetech.wordpress@gmail.com";
              $mail->Password =  "hash.self.super()12@#*";
              $mail->SMTPSecure = "ssl";
              $mail->Port = 465; // for tls

               //email settings
               $mail->isHTML(true);
               $mail->setFrom("ucodetech.wordpress@gmail.com", "E-Log Book portal");
               $mail->addAddress($email);
               // $mail->addReplyTo("ucodetut@gmail.com", "Library Offence Doc.");
               $mail->Subject = 'Email Verification';
               $mail->Body = "
                <div style='width:80%; height:auto; padding:10px; margin:10px'>

            <p style='color: #fff; font-size: 20px; text-align: center; text-transform: uppercase;margin-top:0px'>One Time Password Verification<br></p>
            <p>Hey $fullname! <br><br>

            You have been added to the Federal Polytechnic Idah E-Logbook system!
            Please verify your email address to be able to login.

           <br><hr>
            $token
            <hr>
            <p> Login Details </p>
            <span> Username: $email</span>
            <span> Password: $password</span>

            Note! You are to change your password immediately your first login

          </p>
           </div>
            ";
            if($mail->send())

             $sql = "INSERT INTO verifyEmail (user_uniqueid, token) VALUES ('$unid','$rndno')";
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



// fetch supervisors
if (isset($_POST['action']) && $_POST['action']== 'fatchSupervisor') {
  $data = $supervisor->grabSupervisors();
  if ($data) {
    echo $data;
  }
}


