<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require_once '../../core/init.php';
$user = new User();
$show = new Show();
$validate = new Validate();
$fullname = $user->data()->stud_fname;
$email = $user->data()->stud_email;

$uniqueid = $user->data()->stud_unique_id;

if (isset($_POST['action']) && $_POST['action'] == 'verifyEma') {

   if (Input::exists()) {
    $validation = $validate->check($_POST, array(
      'token' => array(
        'required' => true,
        'min' => 8,
        'max' => 8
      )
    ));
    if ($validation->passed()) {
      $status = "on";
      $token = Input::get('token');
      $sql = "SELECT * FROM verifyEmail WHERE user_uniqueid = '$uniqueid' ";
      $query = Database::getInstance()->query($sql);
      if ($query->count()) {
          $row = $query->first();
          if ($row->token != NULL) {
          $date = date("Y-m-d");
          $cuDate = pretty_dated($row->dateVerified);
          if ($cuDate != $date) {
            echo $show->showMessage('info', 'Verification Token Expired! request another one.', 'warning');
            return false;
          }elseif($token != $row->token){
            echo $show->showMessage('danger', 'Invalid Token!', 'warning');
            return false;

          }else{
                $verified = "UPDATE students SET verified = 1 WHERE stud_unique_id = '$uniqueid' ";
                if(Database::getInstance()->query($verified))
                     echo 'success';


          }
        }else{
           echo $show->showMessage('danger', 'Token is empty request another one!', 'warning');
            return false;
        }

          }else{
            echo $show->showMessage('danger', 'Token Not Found!', 'warning');
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

if (isset($_POST['action']) && $_POST['action'] == 'resendOTP') {

      $sql = "SELECT * FROM verifyEmail WHERE user_uniqueid = '$uniqueid' ";
      $query = Database::getInstance()->query($sql);
      if ($query->count()) {
            $update = "UPDATE verifyEmail SET token = 'Null' WHERE user_uniqueid = '$uniqueid' ";
             Database::getInstance()->query($update);

          }

            $rndno=rand(10000000, 99999999);//OTP generate
            $token = "TOKEN: "."<h2>".$rndno."</h2>";

            // _____________________________________________________

            // ---------------------------------------------------------
                // Load Composer's autoloader
          require APPROOT. '/vendor/autoload.php';
          $mail =  new PHPMailer(true);

            try{

               // //SMTP settings
             
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
               $mail->Subject = 'Email Verification';
               $mail->Body = "
                <div style='width:80%; height:auto; padding:10px; margin:10px'>

            <p style='color: #fff; font-size: 20px; text-align: center; text-transform: uppercase;margin-top:0px'>One Time Password Verification<br></p>
            <p>Hey $fullname! <br><br>

          Here is your token please use the code to verify your email!

           <br><hr>
            $token

          </p>
           </div>
            ";
            if($mail->send())
            $update2 = "UPDATE verifyEmail SET token = '$rndno', dateVerified = NOW()  WHERE user_uniqueid = '$uniqueid' ";
            Database::getInstance()->query($update2);
            echo 'success';

        } catch (\Exception $e) {
            echo $show->showMessage('danger', 'Message could not be sent. Mailer Error:' .$mail->ErrorInfo, 'warning');
            return false;
          }

  }

if (isset($_POST['action']) && $_POST['action'] == 'verifyDevice') {
   if (Input::exists()) {
    $validation = $validate->check($_POST, array(
      'secure_token' => array(
        'required' => true,
        'min' => 8,
        'max' => 8
      )
    ));
    if ($validation->passed()) {
      $token = Input::get('secure_token');
      $sql = "SELECT * FROM secureOtp WHERE user_uniqueid = '$uniqueid' ";
      $query = Database::getInstance()->query($sql);
         if ($query->count()) {
          $row = $query->first();
          if ($row->secure_token != NULL) {
          $date = date("Y-m-d");
          $cuDate = pretty_dated($row->dateSent);
          if ($cuDate != $date) {
            echo $show->showMessage('info', 'Verification Token Expired! request another one.', 'warning');
            return false;
          }elseif($token != $row->secure_token){
            echo $show->showMessage('danger', 'Invalid Token!', 'warning');
            return false;

          }else{
                $verified = "UPDATE secureOtp SET status = 'used' WHERE user_uniqueid = '$uniqueid' ";
                if(Database::getInstance()->query($verified))
                     echo 'success';


          }
        }else{
           echo $show->showMessage('danger', 'Token is empty request another one!', 'warning');
            return false;
        }

          }else{
            echo $show->showMessage('danger', 'Token Not Found!', 'warning');
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



if (isset($_POST['action']) && $_POST['action'] == 'resendOTPdevice') {

      $sql = "SELECT * FROM secureOtp WHERE user_uniqueid = '$uniqueid' ";
      $query = Database::getInstance()->query($sql);
      if ($query->count()) {
            $update = "UPDATE secureOtp SET status = 'unused', secure_token = NULL WHERE user_uniqueid = '$uniqueid' ";
          }
            $rndno=rand(10000000, 99999999);//OTP generate
            $token = "TOKEN: "."<h2>".$rndno."</h2>";

            // _____________________________________________________

            // ---------------------------------------------------------
                // Load Composer's autoloader
          require APPROOT. '/vendor/autoload.php';
          $mail =  new PHPMailer(true);

            try{

               // //SMTP settings
              
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

          Here is your token please use the code to verify your device!

           <br><hr>
            $token

          </p>
           </div>
            ";
            if($mail->send())
            $update = "UPDATE secureOtp SET secure_token = '$rndno', dateSent = NOW()  WHERE user_uniqueid = '$uniqueid' ";
            Database::getInstance()->query($update);
            echo 'success';

        } catch (\Exception $e) {
            echo $show->showMessage('danger', 'Message could not be sent. Mailer Error:' .$mail->ErrorInfo, 'warning');
            return false;
          }

  }
