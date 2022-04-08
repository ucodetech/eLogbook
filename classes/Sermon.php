<?php
/**
 *
 */
class Sermon
{
  private $_db;

  public function __construct()
  {
    $this->_db = Database::getInstance();
  }

//create sermon
public function create($table, $fields = array())
{
  if (!$this->_db->insert($table, $fields)) {
    throw new \Exception("Error Inserting sermon", 1);

  }
}
//update sermon
public function updateSermon($table, $id, $fields = array())
{
  if (!$this->_db->update($table, 'id', $id, $fields)) {
    throw new \Exception("Error updating sermon", 1);

  }
}


//fetch sermon text textFormat
public function fetchSermon($table)
{
  $sermon = $this->_db->get($table, array('deleted', '=', 0));
  if ($sermon->count()) {
    return $sermon->results();
  }else{
    return false;
  }
}

//fetch sermon text  frontend
public function fetchSermonFront()
{
  // $sermon = $this->_db->get('sermonTextFormat', array('published', '=', 1));
  $sql = "SELECT * FROM sermonTextFormat WHERE published = 1 ORDER BY id DESC";
  $sermon = $this->_db->query($sql);  
  if ($sermon->count()) {
    return $sermon->results();
  }else{
    return false;
  }
}

public function searchSermon($search)
{
  $sql =  "SELECT *  FROM sermonTextFormat WHERE title LIKE '%$search%' ";
$this->_db->query($sql);
 if ($this->_db->count()) {
   return $this->_db->results();
 }else{
  return false;
 }
}

//fetch sermon text  frontend
public function fetchSermonSlug($slug)
{
  // $sermon = $this->_db->get('sermonTextFormat', array('published', '=', 1));
  $sql = "SELECT * FROM sermonTextFormat WHERE slug_url = '$slug'";
  $sermon = $this->_db->query($sql);  
  if ($sermon->count()) {
    return $sermon->first();
  }else{
    return false;
  }
}




public function slugCheck($table, $slug_url)
{
  $data = $this->_db->get($table, array('slug_url', '=', $slug_url));
  if ($data->count()) {
    return $data->results();
  }else{
    return false;
  }

}

public function publishAction($table, $actionId, $val)
{
  $this->_db->update($table, 'id', $actionId, array(
    'published' => $val
  ));
  return true;
}

public function deleteAction($table, $actionId, $val)
{
  $this->_db->update($table, 'id', $actionId, array(
    'deleted' => $val
  ));
  return true;
}

public function getDetail($table, $detailid){
    $get = $this->_db->get($table, array('id','=', $detailid));
    if ($get->count()){
        return $get->first();
    }else{
        return false;
    }
}

public function deleteComp($table, $Id)
{
    $val = '';
  $this->_db->update($table, 'id', $Id, array(
    'audio' => $val
  ));
  return true;
}

//check if published before editing
public function publishStatus($table,$id)
{
  $check = $this->_db->get($table, array('id', '=', $id));
  if ($check->count()) {
    return $check->first();
  }else{
    return false;
  }

}

public function getAudio()
{
  $data = $this->_db->get('sermonAudioFormat', array('published', '=', '1'));
  if ($data->count()) {
      $audioFile = $data->results();
          foreach ($audioFile as $audio) {
          
            ?>
                    <script>
                       function toggleDec<?=$audio->id?>() {
                          $('#audioDescription<?=$audio->id?>').toggle();
                        }
                        
                        function fetchDownloads<?=$audio->id?>(){
                          download_id = '<?=$audio->id?>';
                       $.ajax({
                            url:'frontlock/lock.php',
                            method:'post',
                            data:{download_id:download_id},
                            success:function(response){
                              $('#showCount<?=$audio->id?>').html(response);
                            }
                          });         
                     }

                        function likeAudio<?=$audio->id?>(){
                          like_id = '<?=$audio->id?>';
                       $.ajax({
                            url:'frontlock/lock.php',
                            method:'post',
                            data:{like_id:like_id},
                            success:function(response){
                              $('#showLike<?=$audio->id?>').html(response);
                            }
                          });         
                     }

                       setTimeout(function(){
                            fetchDownloads<?=$audio->id?>();
                            likeAudio<?=$audio->id?>();
                        },1000);

                        </script>

        <div class="col-lg-12 audioContainer shadow-lg">
          <div class="row p-3">
            <div class="col-md-5 details">
              <span class="text-left">Author: <?=$audio->author?></span>
              <span class="text-left">Sermon Date:  <?=$audio->dateOfSermon?></span>
              <span class="text-left">Date Posted:  <?=$audio->datePosted?></span>
              <?php if ($audio->dateUpdated == null): ?>
              <span class="text-left">Date Updated:   No update<span>

                <?php else: ?>

                 
                <span class="text-left">Date Updated: <?=$audio->dateUpdated?><span>
              <?php endif ?>
              <button class="btn btn-info btn-sm" onclick="javascript:toggleDec<?=$audio->id?>()">Audio Description</button>
              <div class="container" style="display: none" id="audioDescription<?=$audio->id?>">
              <p class="text-bold text-info">Description: <?=$audio->description?></p>

              </div>

            </div>
            <div class="col-md-4 playerTitleLink">
              <h3 class="text-center"> <?=$audio->title?></h3>

              <hr>
              <p>
                <audio controls>
                  <source src="uploads/sermon/<?=$audio->audio?>"  type="audio/mpeg">
                </audio>
              </p>
            </div>
            <div class="col-md-3 buttons">
              <button class="btn btn-sm  btn-outline-primary"><?=sizeFilter($audio->audioSize)?></button>&nbsp;
              <hr>
                <a class="btn btn-sm  btn-outline-primary" onclick="javascript:fetchDownloads<?=$audio->id?>()" href="frontlock/download/<?=$audio->id?>"><i class="fas fa-cloud-download-alt"></i><span id="showCount<?=$audio->id?>"></span></a>
              &nbsp;
                  <button class="btn btn-sm btn-outline-success"  type="button" onclick="javascript:likeAudio<?=$audio->id?>()" id="likeAudio"><i class="fa fa-thumbs-up  fa-lg"></i><span id="showLike<?=$audio->id?>"></span></button>
             &nbsp;
              <button class="btn btn-sm  btn-outline-info"><i class="fas fa-share-alt"></i></button>

            </div>
          </div>
        </div>

            <?
          }

  }else{
    return '<h2 class="text-center text-dark text-bold">No sermon published yet!</h2>';
  }
}

}//end of class
