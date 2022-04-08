<?php
require_once  '../../core/init.php';
$grapNote = new UserNote();
$feed = new Feedback();
$notify = new Notification();
$show = new Show();

//Fetch Notes Ajax request
if (isset($_POST['action']) && $_POST['action'] == 'fetchAllFeed') {
    $output = '';
    $feeds =  $feed->getFeedback();
    echo $feeds;
}

if (isset($_POST['feeddetails_id'])) {
    $id = $_POST['feeddetails_id'];
    $feeds = $feed->feedDetails($id);
    $user = $grapNote->selectUserNote($feeds->user_id);
    $output = '';
    if ($feeds->replied == 0) {
        $msg = "<span class='text-danger align-self-center lead'>No</span>";
        $answer  = '<a href="#" fid="'.$feeds->id.'" id="'.$feeds->user_id.'" class="btn btn-primary  btn-lg replyFeedbackIcon" title="Reply" data-toggle="modal" data-target="#replyModal"><i class="fa fa-reply fa-lg"></i> </a>';
    }else{
        $msg = "<span class='text-success align-self-center lead'>Yes</span>";
        $answer = "<button class='btn btn-info btn-lg align-self-center lead'>Feedback Replied</button>";
    }
    $output .= '
    <div class="modal-header">
      <h3 class="modal-title" id="getName">
        '.$user->full_name.' - ID: '.$user->id.'
      </h3>
      <button type="button" class="close" data-dismiss="modal" name="button">&times;</button>
    </div>
    <div class="modal-body">
      <div class="card-deck">
        <div class="card border-primary" style="border:2px solid blue;font-size:1.2rem;">
          <div class="card-body" style="font-size:1.2rem;">
            <p style="font-size:1.2rem;"> Email: '.$user->email.' </p>
            <p style="font-size:1.2rem;">Subject: '.$feeds->subject.'</p>
            <p style="font-size:1.2rem;">Feedback: '.$feeds->feedback.' </p>
            <p style="font-size:1.2rem;">Replied: '.$msg.'</p>
            <p style="font-size:1.2rem;">Sent On: '.pretty_date($feeds->dateCreated).'</p>
          </div>
        </div>
        <div class="card align-self-center">
              '.$answer.'
        </div>
      </div>
    </div>
    <div class="modal-footer">
    <span class="align-left">Feedback Detail</span>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
    </div>
    ';


    echo $output;
}

//Reply feedback to user Ajax
if (isset($_POST['message'])) {
    $userid = $_POST['userid'];
    $message = $show->test_input($_POST['message']);
    $feedid = (int)$_POST['feedid'];
    $feed->replyFeedback($userid, $message);
    $feed->updateFeedbackReplied($feedid);
    } catch (\Exception $e) {
      echo $show->showMessage('danger', $e->getMessage(), 'warning');
      return false;
    }


}


// FEtch notification ajax
if (isset($_POST['action']) && $_POST['action'] == 'fetchNotifaction') {

    $notifaction = $notify->fetchNotifactionAdmin();
    $output = '';
    if ($notifaction){
        foreach ($notifaction as $noti) {
            $user = $grapNote->selectUserNote($noti->user_id);
            $output .= '
            <div class="media">
                <img class="d-flex align-self-center img-radius" src="'.URLROOT.'chapel_Members/profile/'.$user->passport.'" alt="'.$user->full_name.'">
                <div class="media-body">
                    <h5 class="notification-user">'.$user->full_name.'</h5>
                    <p class="notification-msg">'.$noti->message.'</p>
                    <span class="notification-time">'.timeAgo($noti->dateCreated).'</span>
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
