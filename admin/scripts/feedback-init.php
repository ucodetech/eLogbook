<?php
require_once  '../../core/init.php';
  $feedback = new Feedback();
  $show = new  Show();
//Fetch Notes Ajax request
if (isset($_POST['action']) && $_POST['action'] == 'fetchAllFeed') {
  $output = '';
  $feeds =  $feedback->getFeedback();
  if ($feeds) {
    echo $feeds;
  }
}

if (isset($_POST['feeddetails_id'])) {
    $id = $_POST['feeddetails_id'];
    $feeds = $feedback->feedDetails($id);
    $user = new User($feeds->user_id);
    $output = '';
    if ($feeds->replied == 0) {
        $msg = "<span class='text-danger align-self-center lead'>No</span>";
        $answer  = '<a href="#" fid="'.$feeds->id.'" id="'.$feeds->user_id.'" class="btn btn-primary btn-block  replyFeedbackIcon" title="Reply" data-toggle="modal" data-target="#replyModal"><i class="fas fa-reply fa-lg"></i> </a>';
    }else{
      $msg = "<span class='text-success align-self-center lead'>Yes</span>";
      $answer = "<span class='btn btn-info btn-lg  align-self-center lead'>Feedback Replied</span>";
    }
    $output .= '
    <div class="modal-header">
      <h3 class="modal-title" id="getName">
        '.$user->data()->full_name.' - ID: '.$user->data()->id.'
      </h3>
      <button type="button" class="close" data-dismiss="modal" name="button">&times;</button>
    </div>
    <div class="modal-body">
      <div class="card-deck">
        <div class="card border-primary" style="border:2px solid blue;">
          <div class="card-body">
            <p> Email: '.$user->data()->email.' </p>
            <p>Subject: '.$feeds->subject.'</p>
            <p>Feedback: '.$feeds->feedback.' </p>
            <p>Replied: '.$msg.'</p>
            <p>Sent On: '.pretty_date($feeds->dateCreated).'</p>
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

  $feedback->replyFeedback($userid, $message);
  $feedback->updateFeedbackReplied($feedid);

}


// FEtch notification ajax
if (isset($_POST['action']) && $_POST['action'] == 'fetchNotifaction') {

  $notifaction = $feedback->fetchNotifaction();
  $output = '';
  if ($notifaction){
    foreach ($notifaction as $noti) {
      $user = new User($noti->user_id);
      $output .= '
      <div class="col-lg-5 align-self-center">
      <div class="alert alert-info" role="alert">
        <button type="button" id="'.$noti->id.'" name="button" class="close" data-dismiss="alert" aria-label="Close">
        <span arid-hidden="true">&times;</span>
      </button>
      <h4 class="alert-heading">New Notification</h4>
      <p class="mb-0 lead">
        '.$user->data()->full_name.'->  '.$noti->message.'
      </p>
      <hr class="my-2">
      <p class="mb-0 float-left">User -> '.$user->data()->full_name.'</p>
      <p class="mb-0 float-right"><i class="lead">'.timeAgo($noti->dateCreated).'</i></p>
      <div class="clearfix"> </div>
    </div>
    </div>
      ';
    }
    echo $output;
  }else{
    echo '<h4 class="text-center text-white mt-5">No New Notifications</h4>';
  }



  }

if (isset($_POST['action']) && $_POST['action'] == 'getNotify') {
    if ($feedback->fetchNotifaction()) {
      $count =  $feedback->fetchNotifactionCount();
      echo '<span class="badge badge-pill badge-danger">'.$count.'</span>';
    }else{
        $count =  $feedback->fetchNotifactionCount();
    echo '<span class="badge badge-pill badge-danger">'.$count.'</span>';
    }
}

if (isset($_POST['delfed_id']) && !empty($_POST['delfed_id'])) {
    $delfed_id = $_POST['delfed_id'];
    $feedback->feedAction($delfed_id);
}
