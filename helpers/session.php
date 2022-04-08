<?php

  function isLoggedInStudent(){
      $user = new User();
    if ($user->isLoggedIn()) {
        return true;
     }else{
        return false;
     }


      }


  function isOTPset($uniqueid){
    $sql = "SELECT * FROM adminOtp WHERE admin_unique
     = '$uniqueid' AND status = 'unused' ";
     $check = Database::getInstance()->query($sql);
    if ($check->count()) {
      return true;
    }else{
      return false;
    }
  }

  function isOTPsetUser($uniqueid){
    $sql = "SELECT * FROM secureOtp WHERE user_uniqueid = '$uniqueid' AND status = 'unused' ";
     $check = Database::getInstance()->query($sql);
    if ($check->count()) {
      return true;
    }else{
      return false;
    }
  }


function isIsLoggedIn(){
      $admin = new Admin();
      if ($admin->isIsLoggedIn()){
          return true;
      }else{
          return  false;
      }
}

function  otpCheck(){
  $admin = new Admin();
  if (isIsLoggedIn()){
    if ($admin->data()->admin_email_verified=="no") {
      return true;
    }else{
      return false;
    }
  }
}

function  verifyCheck(){
  $user = new User();
  if (isLoggedInStudent()){
    if ($user->data()->verified==0) {
      return true;
    }else{
      return false;
    }
  }
}


// industrial base supervisor
function issuperIsLoggedIn(){
      $inds = new Supervisor();
      if ($inds->superIsLoggedIn()){
          return true;
      }else{
          return  false;
      }
}

function  verifyCheckInd(){
  $inds = new Supervisor();
  if (issuperIsLoggedIn()){
    if ($inds->superdata()->verified==0) {
      return true;
    }else{
      return false;
    }
  }
}






function hasPermissionSuper($permission = 'superuser'){
    $admin = new Admin();
    if (isset($_SESSION[Config::get('session/session_admin')])) {

    $permissioned = $admin->data()->admin_permissions;

    $permissions = explode(',', $permissioned);
     if (in_array($permission, $permissions,true)) {
      return true;
     }
     return false;

   }
}

function hasPermissionSupervisor($permission = 'supervisor'){
     $admin = new Admin();
    if (isset($_SESSION[Config::get('session/session_admin')])) {

    $permissioned = $admin->data()->admin_permissions;

    $permissions = explode(',', $permissioned);
     if (in_array($permission, $permissions,true)) {
      return true;
     }
     return false;

   }

}

function hasPermissionInds($permission = 'indsupervisor'){
     $indSupervisors = new Supervisor();
    if (isset($_SESSION[Config::get('session/session_supervisor')])) {

    $permissioned = $indSupervisors->superdata()->permissions;

    $permissions = explode(',', $permissioned);
     if (in_array($permission, $permissions,true)) {
      return true;
     }
     return false;

   }

}
