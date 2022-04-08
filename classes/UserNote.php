<?php
/**
 * post class
 */
class UserNote
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

public function addNote($fields = array())
{
	if(!$this->_db->insert('notes', $fields)){
		throw new Exception("Error Processing Request", 1);
	}

}

public function displayNote(){
  $userid = $this->userNow()->getUserId();
  $sql = "SELECT * FROM notes WHERE user_id = '$userid' AND deleted = 0 ";
  $data = $this->_db->query($sql);
  if ($data->count()) {
      return $this->_db->results();
    }else{
      return false;
    }

}

//Select Note for Edit note
public function editNote($id){
	$this->_db->get('notes', array('id', '=', $id));
	if ($this->_db->count()) {
		return $this->_db->first();
	}else{
		return false;
	}
}

//Update Note

public function updateNote($id, $title, $note){
  $sql = "UPDATE notes SET title = '$title', note = '$note', dateUpdated = NOW() WHERE id = '$id' ";
  if($this->_db->query($sql))
  return true;
  else
  	return false;
}

//Delete Note

public function deleteNote($id){
	$this->_db->update('notes','id', $id, array(
		'deleted' => 1
	));

  return true;
}
//restore note
public function restoreNote($id){
	$this->_db->update('notes','id', $id, array(
		'deleted' => 0
	));

  return true;
}

public function deleteNoteP($id){
	$this->_db->delete('notes', array(
		'id', '=', $id
	));

  return true;
}

public function getNoteDeleted($userid){
  $sql = "SELECT * FROM notes WHERE user_id = '$userid' AND deleted = 1";
  $this->_db->query($sql);
  if ($this->_db->count()) {
  	 $notes = $this->_db->results();
  	 $output = '';
  if (!$notes) {
    echo '<h3 class="text-center text-secondary">Nothing in the trash can! <a href="dashboard"><i class="fa fa-tachometer fa-lg" aria-hidden="true"></i> Dashboard</a></h3>';
  }else{
    $output .= '
    <table id="showNotes" class="table table-striped table-sm">
      <thead>
        <tr>
          <th>#</th>
          <th>Title</th>
          <th>Note</th>
          <th>Action</th>
        </tr>
        <tbody>
    ';
    $x = 0;
    foreach ($notes as $note) {
      $x = $x + 1;
    $output .= '
    <tr>
      <td>'.$x.'</td>
      <td>'.$note->title.'</td>
      <td>'.wrap($note->note).'...</td>
      <td>
        <a href="#" id="'.$note->id.'"  title="View Details" class="text-success infoBtn"><i class="fas fa-info-circle fa-lg"></i> </a>&nbsp;

      <a href="#" id="'.$note->id.'" title="Delete Note" class="text-danger deleteBtn"><i class="fa fa-trash fa-lg"></i> </a> &nbsp;

      <a href="#" id="'.$note->id.'" title="Restore Note" class="text-warning restoreBtn"><i class="fa fa-refresh fa-lg" aria-hidden="true"></i> </a>
      </td>
    </tr>
    ';

    }
    $output .='
    </tbody>
  </thead>
</table>
    ';
  }
  return  $output;


  }

}

// Fetch all notes from user
public function getNotes(){
  $sql = "SELECT notes.id, notes.title, notes.note, notes.dateCreated, notes.dateUpdated, users.full_name, users.email FROM notes INNER JOIN users ON notes.user_id = users.id ";
  $data = $this->_db->query($sql);
  $output = '';
  if ($data->count()) {
    $notes =  $data->results();
      $output .= '
      <table id="showNotes" class="table table-striped table-sm">
        <thead>
          <tr>
            <th>#</th>
            <th>User Name</th>
            <th>User Eamil</th>
            <th>Note Title</th>
            <th>Note</th>
            <th>Written On</th>
            <th>Updated On </th>
            <th>Action</th>
          </tr>
          <tbody>
      ';
      $x = 0;
      foreach ($notes as $note) {
        $x = $x + 1;
      $output .= '
      <tr>
        <td>'.$x.'</td>
        <td>'.$note->full_name.'</td>
        <td>'.$note->email.'</td>
        <td>'.$note->title.'</td>
        <td>'.wrap($note->note).'...</td>
        <td>'.pretty_date($note->dateCreated).'</td>
        <td>'.pretty_date($note->dateUpdated).'</td>
        <td>
          <a href="#" id="'.$note->id.'"  title="View Details" class="text-success infoBtn"><i class="fas fa-info-circle fa-lg"></i> </a>&nbsp;

          <a href="#" id="'.$note->id.'" title="Move Note to trash" class="text-danger deleteBtn"><i class="fa fa-recycle fa-lg"></i> </a>
        </td>
      </tr>
      ';

      }
      $output .='
      </tbody>
    </thead>
  </table>
      ';

return $output;

}else{
    echo '<h3 class="text-center text-secondary">Users have not written any note!</h3>';
    return false;

}
}
//Delete Note

public function noteAction($id, $val){
  $this->_db->update('notes', 'id', $id, array(
    'deleted' => $val
  ));
  return true;
}


public function feedDetails($id){
  $data = $this->_db->get('feedback', array('id', '=', $id));
  if ($data->count()) {
      return $this->_db->first();
  }else{
    return false;
  }

}

public function selectUserNote($userid){
  $data = $this->_db->get('members', array('id', '=', $userid));
  if ($data->count()) {
      return $this->_db->first();
  }else{
    return false;
  }
}



}//end of class
