<?php
/**
 * post class
 */
class Notification
{
  private  $_db,
           $_user;


  function __construct()
  {
    $this->_db = Database::getInstance();
   $this->_user = new User() ;

  }

  public function userNow()
  {
   return $this->_user;
  }

public function notifi($fields = array())
{
	if(!$this->_db->insert('notifications', $fields)){
		throw new Exception("Error Logging notification", 1);
	}
}

public function fetchNotifaction($userid){
   $output = '';
   $sql = "SELECT * FROM notifications WHERE user_id = '$userid' AND type = 'user' ORDER BY id DESC";
   $checked = $this->_db->query($sql);
   if ($checked->count()) {
     return  $checked->results();
  }else {
    return false;
  }
}


public function fetchNotifactionCount(){
   $user_id = $this->userNow()->getUserId();
   $output = '';
  $sql = "SELECT * FROM notifications WHERE user_id = '$user_id' AND type = 'user'";
    $this->_db->query($sql);
   if ($this->_db->count()) {
      $count = $this->_db->count();
      $output .= '<span class="badge badge-pill badge-danger">'.$count.'</span>';

   }else{
    $count = 0;
    $output .= '<span class="badge badge-pill badge-danger">'.$count.'</span>';
   }
   return $output;

}


  //FEtch notification from database admin
  public function fetchNotifactionAdmin(){
    $sql = "SELECT * FROM notifications WHERE type = 'admin' ORDER BY id DESC";
     $this->_db->query($sql);
    if ($this->_db->count()) {
      return $this->_db->results();
    }else{
      return false;
  }
}


  public function fetchNotifactionCountAdmin(){
    $sql = "SELECT * FROM notifications";
     $this->_db->query($sql);
      $output = '';
      if ($this->_db->count()) {
          $count = $this->_db->count();
          $output .= '<span class="badge badge-pill badge-danger">'.$count.'</span>';

      }else{
          $count = 0;
          $output .= '<span class="badge badge-pill badge-danger">'.$count.'</span>';
      }
      return $output;

  }
  //Delete notification
    public function removeNotificationAdmin($id){
      $this->_db->delete('notifactions', array('id', '=', $id));
      return true;
    }

    //Delete notification
      public function removeNotification($id){
        $sql = "DELETE FROM notifications WHERE id = '$id' AND type = 'user'";
        $this->_db->query($sql);
        return true;
      }



}//end of class
