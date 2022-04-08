<?php
/**
 * post class
 */
class Feedback
{
  private  $_db,
           $_user;


  public function __construct()
  {
    $this->_db = Database::getInstance();
   $this->_user = new User() ;

  }

  public function userNow()
  {
   return $this->_user;
  }

public function feedBack($fields = array())
{
	if(!$this->_db->insert('feedback', $fields)){
		throw new Exception("Error Processing Request", 1);
	}
}

public function feedAction($id){
  $this->_db->delete('feedback', array('id', '=', $id));
  return true;

}

// Fetch all notes from user
public function getFeedback(){
  $sql = "SELECT feedback.id, feedback.subject, feedback.feedback, feedback.dateCreated, feedback.replied,feedback.user_id, members.full_name, members.email FROM feedback INNER JOIN members ON feedback.user_id = members.id WHERE feedback.deleted = 0";
$data = $this->_db->query($sql);
$output = '';
if ($data->count()) {
      $feeds = $this->_db->results();
      if (!$feeds) {
        echo '<h3 class="text-center text-secondary">No Feedback from users!</h3>';
      }else{

        $output .= '
        <table id="showFeed" class="table table-striped table-lg">
          <thead>
            <tr>
              <th>#</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>Feedback Subject</th>
              <th>Feedback</th>
              <th>Sent On</th>
              <th>Replied</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
        ';
        $x = 0;
        foreach ($feeds as $feed) {
          if ($feed->replied == 0) {
              $msg = "<p class='text-danger align-self-center lead'>No</p>";
          }else{
            $msg = "<p class='text-success align-self-center lead'>Yes</p>";
          }
          $x = $x + 1;
        $output .= '
        <tr>
          <td>'.$x.'</td>
          <td>'.$feed->full_name.'</td>
          <td>'.$feed->email.'</td>
          <td>'.$feed->subject.'</td>
          <td>'.wrap($feed->feedback).'...</td>
          <td>'.pretty_date($feed->dateCreated).'</td>
          <td>'.$msg.'</td>
          <td>
            <a href="#" id="'.$feed->id.'"  title="View Details" class="text-success feedBackinfoBtn"  data-toggle="modal" data-target="#showFeedDetailsModal"><i class="fa fa-info-circle fa-lg"></i> </a>&nbsp;

            <a href="#" id="'.$feed->id.'" title="Delete Feedback" class="text-danger feedBackdeleteBtn"><i class="fa fa-trash fa-lg"></i> </a>
          </td>
        </tr>
        ';

        }
        $output .='
        </tbody>
    </table>
        ';

      }
return $output;

}


}


public function feedDetails($id){
  $this->_db->get('feedback', array('id', '=', $id));
  if ($this->_db->count()) {
    return $this->_db->first();
  }else{
    return false;
  }

}


//Reply to user feedback
public function replyFeedback($userid, $message){
  $this->_db->insert('notifications', array(
    'user_id' => $userid,
    'type' => 'user',
    'message' => $message
  ));
  return true;
}

public function notifyMe($userid, $message){
  $this->_db->insert('notifications', array(
    'user_id' => $userid,
    'type' => 'admin',
    'message' => $message
  ));
  return true;
}


public function updateFeedbackReplied($feedid){
  $this->_db->update('feedback','id', $feedid , array(
    'replied' => 1
  ));
  return true;
}





}
