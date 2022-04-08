<?php
    require_once '../../core/init.php';
    $admin = new Admin();
    $show = new Show();
    $error = array();
    $general = new General();
    $validate = new Validate();

$admin_id = $admin->data()->id;

if (isset($_POST['action']) && $_POST['action'] == 'updatePro') {


    if (Input::exists()) {
      $validation = $validate->check($_POST, array(
        'admin_fullname' => array(
          'required' => true,
          'min' => 5,
          'max' => 100
        ),
        'admin_phone_no' => array(
          'required' => true,
          'min' => 11,
          'max' => 15
        )
      ));
      if ($validation->passed()) {
        $admin->updateAdminRecored($admin_id,  array(
          'admin_fullname' => Input::get('admin_fullname'),
          'admin_phone_no' => Input::get('admin_phone_no')
        ));
        echo 'success';
      }else{
        foreach ($validation->errors() as $error) {
          echo $show->showMessage('danger', $error, 'warning');
          return false;
        }
      }
    }



}




//change Password
if (isset($_POST['action']) && $_POST['action'] == 'change_password') {
  $currentP = $show->test_input($_POST['admin_password']);
  $newP = $show->test_input($_POST['admin_new_password']);
  $cnewP = $show->test_input($_POST['admin_cnew_password']);

  $password = $admin->data()->admin_password;
   if ($currentP == '') {
    echo $show->showMessage('danger', 'Current Password is required!', 'warning');
    return false;
  }

  if ($newP == '') {
    echo $show->showMessage('danger', 'New Password is required!', 'warning');
    return false;
  }else{
      if (strlen($newP) < 10) {
        echo $show->showMessage('danger', 'Password must be atleast 10 characters long!', 'warning');
        return false;
      }
  }
  if ($cnewP == '') {
    echo $show->showMessage('danger', 'Please verify new password!', 'warning');
    return false;
  }else{
    if ($cnewP != $newP) {
      echo $show->showMessage('danger', 'Password Mismatch!', 'warning');
      return false;
    }
  }

  $hashNewPass = password_hash($newP, PASSWORD_DEFAULT);
  if ($currentP == '') {
    echo $show->showMessage('danger', 'Current Password is required!', 'warning');
    return false;
  }else{
    if (!password_verify($currentP, $password)) {
      echo $show->showMessage('danger', 'Current Password is not correct!', 'warning');
      return false;
    }else{
      try {
          $admin->change_password($admin_id, array(
              'admin_password' => $hashNewPass
          ));
          $admin->logout();
          echo 'changed';
      } catch (Exception $e) {
        echo $show->showMessage('danger', $e->getMessage(), 'warning');
        return false;
      }
     
    

    }
  }
}

//verify email
if (isset($_POST['action']) && $_POST['action'] == 'verify_email') {
  $token = md5(microtime());
  $url = "http://localhost/ucodeTuts/users/verify_email.php?token=".$token;
  $userid = $user->getUserId();
  $deleteUser = $user->deleteVkey($userid);
  $updateUser = $user->updateVkey($token, $userid);
  $notify->notifi(array(
        'user_id' => $userid,
        'type' => 'Admin',
        'message' => 'Verified E-mail!'
      ));


  if ($updateUser===true) {
    try {
            $useremail  = $user->getEmail();
            $mail =  new PHPMailer\PHPMailer\PHPMailer();
               //SMTP settings
               $mail->isSMTP();
               $mail->Host = "smtp.gmail.com";
               $mail->SMTPAuth = true;
               $mail->Username = "ucodetech.wordpress@gmail.com";
               $mail->Password =  "echo@mike12@@";
               $mail->Port = 587; //587 for tls
               $mail->SMTPSecure = "tls";

               //email settings
               $mail->isHTML(true);
               $mail->setFrom("ucodetech.wordpress@gmail.com");
               $mail->addAddress($useremail);
               $mail->addReplyTo("ucodetech.wordpress@gmail.com");
               $mail->Subject = "E-Mail verification";
               $mail->Body = "
            <div style='width:80%; height:auto; padding:10px; margin:10px'>
        <p align='center'><img src='http://localhost/ucodeTuts/images/ucodeTut%20Logo.png' class='img-fluid' width='300' alt='Ucode Logo' align='center'>  </p>
        <p style='color: #fff; font-size: 20px; text-align: center; text-transform: uppercase;margin-top:0px'>E-Mail verification</p>
        <p  style='color: #000; font-size: 18px; text-transform:capitalize;margin:10px;  '>
        Here is your E-mail verification link:<br><hr>
        <a  href='".$url."' style='margin-bottom: 0;font-weight: 400;text-align: center;white-space: nowrap;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;cursor: pointer;background-image: none;border: 1px solid transparent;padding: 6px 12px;font-size: 14px;line-height: 1.42857143;border-radius: 4px;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;  color: #fff;background-color: #d9534f;border-color: #d43f3a; text-decoration: none; font-size: 20px;'>Verify Email</a>
        <hr>
        <hr>
        <h4>UcodeTuts</h4>
        <p style='color: #fff; font-size:20px; text-align: center; text-transform: uppercase;'>
        <a href='http//uzbgraphix.com.ng' style='color:#fff;'>Offical Site</a></p> </div>
        ";
        $mail->send();
        echo $show->showMessage('success', 'We have sent a verification link to you! check your mail-box', 'check');
        } catch (\Exception $e) {
        echo $show->showMessage('danger', 'Something went wrong please try again!', 'warning');
        }
      }
}
