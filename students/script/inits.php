<?php
require_once  '../../core/init.php';
$grapNote = new UserNote();
$feed = new Feedback();
$notify = new Notification();
$show = new Show();
$user = new User();



// FEtch notification ajax
if (isset($_POST['action']) && $_POST['action'] == 'fetchNotifaction') {
  $user_id = $user->data()->id;
    $notifaction = $notify->fetchNotifaction($user_id);
    $output = '';
    if ($notifaction){
        foreach ($notifaction as $noti) {
            $user = $grapNote->selectUserNote($noti->user_id);
            $output .= '
            <div class="media">
                <img class="d-flex align-self-center img-radius" src="'.URLROOT.'chapel_Members/profile/admin.png" alt="admin">
                <div class="media-body">
                    <h5 class="notification-user">From Admin</h5>
                    <p class="notification-msg">'.$noti->message.'</p>
                    <span class="notification-time">'.timeAgo($noti->dateCreated).'</span> <hr>
                    <a href="member-inbox" clas="text-info">Go to inbox</a> <hr class="invisible">
                    <button type="button" id="'.$noti->id.'" name="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span arid-hidden="true">&times;</span>
                  </button>
                </div>
            </div>
      ';
        }
        echo $output;
    }else{
        echo '<h4 class="text-center text-info mt-5">No New Notifications</h4>';
    }



}

if (isset($_POST['action']) && $_POST['action'] == 'getNotify') {
    if ($notify->fetchNotifactionAdmin()) {
        $count =  $notify->fetchNotifactionCountAdmin();
        echo '<span class="badge badge-pill badge-danger">'.$count.'</span>';
    }else{
        $count =  $notify->fetchNotifactionCountAdmin();
        echo '<span class="badge badge-pill badge-danger">'.$count.'</span>';
    }
}



//remove notifatications
if (isset($_POST['notifacation_id'])) {
  $id = $_POST['notifacation_id'];
  ;
  $notify->removeNotification($id);
}


if(isset($_POST['action']) && $_POST['action'] == "update_time"){
    $id =  $user->data()->id;
    $d = $user->activity($id);
}



// FEtch notification ajax
if (isset($_POST['action']) && $_POST['action'] == 'inbox') {
  $user_id = $user->data()->id;
    $notifaction = $notify->fetchNotifaction($user_id);
    $output = '';
    if ($notifaction){
        $i = 0;
        foreach ($notifaction as $noti) {
            $i = $i + 1;
            $user = $grapNote->selectUserNote($noti->user_id);
            $output .= '
         <div id="accordion'.$noti->id.'" role="tablist" aria-multiselectable="true" >
        <div class="accordion-panel pb-4" >
            <div class="accordion-heading" role="tab" id="headingOne'.$noti->id.'">
                <h3 class="card-title accordion-title" >
                    <a class="accordion-msg waves-effect waves-light scale_active collapsed" data-toggle="collapse" data-parent="#accordion'.$noti->id.'" href="#collapseOne'.$noti->id.'" aria-expanded="false" aria-controls="collapseOne'.$noti->id.'">
                    <p style="font-size:1.2rem;">
                 ('.$i.')  New Message '.timeAgo($noti->dateCreated).' 
                   </p>
                </a>
            </h3>
        </div>
        <div id="collapseOne'.$noti->id.'" class="panel-collapse in collapse" role="tabpanel" aria-labelledby="headingOne'.$noti->id.'" style="">
            <div class="accordion-content accordion-desc">
                <p style="font-size:1.2rem;">
                    '.$noti->message.'
                </p>
                 <button type="button" id="'.$noti->id.'" name="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span arid-hidden="true" class="text-danger">&times; Read</span>
                  </button>
            </div>
        </div>
    </div>

</div>
      ';
        }
        echo $output;
    }



}