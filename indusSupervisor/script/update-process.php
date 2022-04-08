<?php
require_once  '../../core/init.php';
$supervisor = new Supervisor();
$show = new Show();
$validate = new Validate();
$general = new General();
$supervisor_id = $supervisor->superdata()->unique_id;

if (isset($_POST['action']) && $_POST['action'] == 'update_details'){
    if (Input::exists()){
        $validation = $validate->check($_POST, array(
            "phoneNo" => array(
                'required' => true,
                'min' => 11,
                'max' => 15

            ),
            "fullname" => array(
                'required' => true,
            ),
            "company_location" => array(
                'required' => true,
            ),
            
        ));

        if ($validation->passed()){
        
            $phoneNo = Input::get('phoneNo');
            $fullname = Input::get('fullname');
            $company_location = Input::get('company_location');

            $sql = "UPDATE inds_supervisors SET phoneNo = '$phoneNo', fullname = '$fullname', company_location = '$company_location' WHERE unique_id = '$supervisor_id' ";
          // print_r($sql);
          // die();
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

  $currentP = $show->test_input($_POST['ind_password']);
  $newP = $show->test_input($_POST['ind_new_password']);
  $cnewP = $show->test_input($_POST['ind_cnew_password']);

  $password = $supervisor->superdata()->password;
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
          $supervisor->change_password($supervisor_id,$hashNewPass);
          $supervisor->logout();
          echo 'changed';
      } catch (Exception $e) {
        echo $show->showMessage('danger', $e->getMessage(), 'warning');
        return false;
      }
     
    

    }
  }
}