<?php

require_once  '../../core/init.php';
$user = new User();
$show = new Show();
$validate = new Validate();
$general = new General();
$user_id = $user->data()->stu_id;

if (isset($_POST['action']) && $_POST['action'] == 'update_details'){
    if (Input::exists()){
        $validation = $validate->check($_POST, array(
            "stud_tel" => array(
                'required' => true,

            ),
            "stud_email" => array(
                'required' => true,
            ),
            
        ));

        if ($validation->passed()){
           $dateTime = date("Y-m-d  h:i:s");
         

            $stud_tel = Input::get('stud_tel');
            $stud_email = Input::get('stud_email');
            $sql = "UPDATE students SET stud_tel = '$stud_tel', stud_email = '$stud_email', made_update = 1, made_update_date = '$dateTime' WHERE stu_id = '$user_id' ";
          
            if(Database::getInstance($sql))
                echo 'success';
        }else{
            foreach($validation->errors() as $error){
                echo $show->showMessage('danger',$error, 'warning');
                return false;
            }
        }
    }


}


//change Password
if (isset($_POST['action']) && $_POST['action'] == 'change_password') {

  $currentP = $show->test_input($_POST['stud_password']);
  $newP = $show->test_input($_POST['stud_new_password']);
  $cnewP = $show->test_input($_POST['stud_cnew_password']);

  $password = $user->data()->stud_password;
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
          $user->change_password($user_id,$hashNewPass);
          $user->logout();
          echo 'changed';
      } catch (Exception $e) {
        echo $show->showMessage('danger', $e->getMessage(), 'warning');
        return false;
      }
     
    

    }
  }
}