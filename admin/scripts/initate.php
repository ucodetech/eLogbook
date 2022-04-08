<?php
require_once '../../core/init.php';
$general = new General();
$admin = new Admin();


if(isset($_POST['action']) && $_POST['action']== "fetch_data"){
    $output = '';

    $row = $general->loggedUsers();

   if ($row) {
     foreach ($row as $active) {

       ?>
       <div class="col-lg-4">

          <img src='../students/profile/<?=$active->passport;?>' width='70px' height='70px' style='border-radius:50px;' alt='Passport'>
         <br>
         <?
         $active->stud_fname . '- ID-' . $active->id ;
         ?>

       </div>
       <?
     }
   }else{

       echo '<span class="text-center">No active Student</span>';

   }


}



if(isset($_POST['action']) && $_POST['action'] == "fetch_super"){
    $output = '';

    $supers = $admin->loggedAdmin();
   if ($supers) {
     foreach ($supers as $active) {

       echo '
         <div class="align-middle  col-md-3">
             <img src="profile/'.$active->admin_passport.'" alt="'.$active->admin_fullname.'" class="img-fluid align-top m-r-15 activeImg">
             <div class="d-inline-block text-center">
                 <h6>'.strtok($active->admin_fullname,' ').'</h6>
                 <p class="text-dark m-b-0">'.strtok($active->admin_permissions, ',').'</p>
             </div>
         </div>
      ';
     }
   }


}


if (isset($_POST['action']) && $_POST['action'] == 'update_admin') {
  $admin->updateAdminLog($admin->data()->id);

}

//
if(isset($_POST['action']) && $_POST['action'] == "totUser"){
  $tot =  $general->totalCount('students');
   echo $tot;
}
//
if(isset($_POST['action']) && $_POST['action'] == "totfeed"){
  $tot =  $general->totalCount('feedback');
   echo $tot;
}

if(isset($_POST['action']) && $_POST['action'] == "totSuperv"){
  $tot =  $general->totalCount('supervisors');
   echo $tot;
}
if(isset($_POST['action']) && $_POST['action'] == "totNotification"){
  $tot =  $general->totalCount('notification');
   echo $tot;
}
// if(isset($_POST['action']) && $_POST['action'] == "totVemail"){
//   $tot =  $student->verified_users(0);
//    echo $tot;
// }
// if(isset($_POST['action']) && $_POST['action'] == "totVdemail"){
//   $tot =  $student->verified_users(1);
//    echo $tot;
// }
// if(isset($_POST['action']) && $_POST['action'] == "totPwdReset"){
//   $tot =  $general->totalCount('pwdReset');
//    echo $tot;
// }
// if(isset($_POST['action']) && $_POST['action'] == "totAUemail"){
//   $tot =  $general->verified_admin(0);
//    echo $tot;
// }
//
// if(isset($_POST['action']) && $_POST['action'] == "totAemail"){
//   $tot =  $general->verified_admin(1);
//    echo $tot;
// }
