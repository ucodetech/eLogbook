<?php
/**
 * comment class
 */
class Comment
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


//Send comment
public function sendComment($fields = array()){
	if (!$this->_db->insert('comment', $fields)) {
		throw new Exception("Error Processing Request", 1);
		
	}
  
}

public function getLikeCount($tutid)
{
   $sql = "SELECT * FROM commentLike WHERE post_id = '$tutid'";
    $this->_db->query($sql);
      if ($this->_db->count()) {
        return $this->_db->count();
      }else{
        return $count = 0;
      }

}

public function checkUserLike($tutid, $cuserid)
{
  $sql = "SELECT * FROM commentLike WHERE post_id = '$tutid' AND user_id ='$cuserid' ";
    $this->_db->query($sql);
      if ($this->_db->count()) {
        return true;
      }else{
        return false;
      }
  
}

public function likeSys($pid, $uid)
{
  $this->_db->insert('commentLike', array(
    'post_id' => $pid,
    'user_id' => $uid
  ));
  return true;
  
}
public function UnlikeSys($pid, $uid)
{
  $sql = "DELETE FROM commentLike WHERE post_id = '$pid' AND user_id = '$uid' ";
  $this->_db->query($sql);
  return true;
  
}

//fetch comment
public function getComment($sermon_id){
  $sql = "SELECT * FROM `comment` WHERE parent_comment_id = 0 AND sermon_id = '$sermon_id'  ORDER BY comment_id DESC ";
  $this->_db->query($sql);
  if ($this->_db->count()) {
  	$result = $this->_db->results();
  $output = '';
  foreach ($result as $row) {
    $output .= '
   <div class="card">
          <div class="card-header bg-success text-light">
            <i class="fa fa-user"></i> By <b>'.$row->comment_sender_name.' </b> <span class="text-warning on"> on</span>  <span><i>'.pretty_dates($row->comment_date).'</i></span>
          </div>
          <div class="card-body">
            <p class="comment">'.$row->comment.'</p>
     
          </div>
          <div class="card-footer bg-light">';
        

        $output .= '<button type="button" class="btn btn-xs btn-outline-warning reply" id="'.$row->comment_id.'" style="float:right;"><i>Reply</i></button>
          </div>
    </div>';
    $output .= $this->get_reply_comment($row->comment_id);

  }
  return $output;

  
  }

}

public function get_reply_comment($parent_id = 0, $marginleft = 0){
    $output = '';
    $sql = "SELECT * FROM `comment` WHERE parent_comment_id = '$parent_id' ";
   $this->_db->query($sql);
   $result = $this->_db->results();
   if ($this->_db->count()) {
       if ($parent_id == 0) {
      $marginleft = 0;
    }else{
      $marginleft = $marginleft + 48;
    }
  
      foreach ($result as $row) {
        $output .='
          <div class="card" style="margin-left:'.$marginleft.'px">
          <div class="card-header bg-info text-light">
            <i class="fa fa-user"></i> By <b>'.$row->comment_sender_name.' </b> <span class="text-warning on"> on</span>  <span><i>'.pretty_dates($row->comment_date).'</i></span>
          </div>
          <div class="card-body">
            <p class="comment">'.$row->comment.'</p>
     
          </div>
        <div class="card-footer bg-light">';
      
           $output.='<button type="button" class="btn btn-xs btn-outline-warning reply" id="'.$row->comment_id.'"  style="float:right;"><i>Reply</i></button>
          </div>
    </div>
        ';
        $output .= $this->get_reply_comment($row->comment_id, $marginleft);
      }
   }

 
    
    return $output;

  }



}