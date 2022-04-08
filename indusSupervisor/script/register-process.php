<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once  '../../core/init.php';
$user = new User();
$validate = new Validate();
$show = new Show();

if (isset($_POST['action']) && $_POST['action'] == 'register'){
    if (Input::exists()){
        $validation = $validate->check($_POST, array(

            'stud_fname' => array(
                'required' => true,
                'min' => 5,
                'max' => 20
            ),
            'stud_lname' => array(
                'required' => true,
                'min' => 5,
                'max' => 20
            ),
            'stud_email' => array(
                'required' => true,
                'unique' => 'students'
            ),
            'stud_tel' => array(
                'required' => true,
                 'unique' => 'students'
            ),
            'stud_school' => array(
              'required' => true,
            ),
            'stud_department' => array(
              'required' => true
            ),
            'stud_level' => array(
              'required' => true,
              'min' => 2,
              'max' => 2
            ),
            'stud_regNo' => array(
                'required' => true
            ),
            'password' => array(
                'required' => true,
                'min' => 10
            ),
            'cpassword' => array(
              'required' => true,
              'matches' => 'password'
            )
        ));
        if ($validation->passed()){
            if (!filter_var(Input::get('stud_email'), FILTER_VALIDATE_EMAIL)){
                echo $show->showMessage('danger', 'Invalid Email address!', 'warning');
                return false;
            }

            $stud_level = Input::get('stud_level');
            $stud_level = strtoupper($stud_level);
            if ($stud_level!= 'ND') {
                echo $show->showMessage('danger', 'please check your level', 'warning');
                return false;
            }
            
            $password = Input::get('password');
            $newPassword = password_hash($password, PASSWORD_DEFAULT);
            $rn = rand(10000000, 99999999);
            $stud_unique_id = 'elog-' . $rn;
         
            try {
                $user->create(array(
                    'stud_fname' => Input::get('stud_fname'),
                    'stud_lname' => Input::get('stud_lname'),
                    'stud_oname' => Input::get('stud_oname'),
                    'stud_email' => Input::get('stud_email'),
                    'stud_tel' => Input::get('stud_tel'),
                    'stud_school' => Input::get('stud_school'),
                    'stud_password' => $newPassword,
                    'stud_dept' => Input::get('stud_department'),
                    'stud_regNo' => Input::get('stud_regNo'),
                    'stud_level' => Input::get('stud_level'),
                    'stud_unique_id' => $stud_unique_id
                ));
                  $rndno=rand(10000000, 99999999);//OTP generate
                $token = "TOKEN: "."<h2>".$rndno."</h2>";
                $fname = Input::get('stud_fname');
                $email = Input::get('stud_email');
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
              $mail->Password =  "hash.self.super()12@#";
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
            <p>Hey $fname! <br><br>

            Your registration was successfull. please enter the code blow on ur token page to verify your email.

           <br><hr>
            $token           
          </p>
           </div>
            ";
            if($mail->send())

             $sql = "INSERT INTO verifyEmail (user_uniqueid, token) VALUES ('$stud_unique_id','$rndno')";
              Database::getInstance()->query($sql);
              echo 'success';

        } catch (\Exception $e) {
            echo $show->showMessage('danger', 'Message could not be sent. Mailer Error:' .$mail->ErrorInfo, 'warning');
            return false;
          }

        }catch( Exception $e){
            echo $show->showMessage('danger', $e->getMessage(), 'warning');
            echo $show->showMessage('danger', $e->getLine(), 'warning');
            echo $show->showMessage('danger', $e->getCode(), 'warning');
            return false;

        }

        }else{
            foreach($validation->errors() as $error){
                echo $show->showMessage('danger', $error, 'warning');
                return false;
            }
        }
    }
}
