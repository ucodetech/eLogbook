<?php

 /**
  * category
  */
 class Category
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


public function getCategory($cate)
{
	 $sql = "SELECT * FROM categories WHERE category = '$cate'  AND deleted = 0 ";
      $this->_db->query($sql);
      if ($this->_db->count()) {
      	return $this->_db->first();

      }else{
      	return false;
      }
}

public function childCate($parentID)
{
  $sql = "SELECT * FROM categories WHERE parent = '$parentID' AND deleted = 0 ";
      $this->_db->query($sql);
      if ($this->_db->count()) {
        return $this->_db->results();

      }else{
        return false;
      }
}

public function getCategoryParent()
{
  $sql = "SELECT * FROM categories WHERE parent = 0 AND deleted = 0 ORDER BY category";
      $this->_db->query($sql);
      if ($this->_db->count()) {
      	return $this->_db->results();

      }else{
      	return false;
      }
}
public function getCategoryCount($cate)
{
	 $sql = "SELECT * FROM categories WHERE category = '$cate'  AND deleted = 0 ";
      $this->_db->query($sql);
      if ($this->_db->count()) {
      	return true;

      }else{
      	return false;
      }
}


//Check exsisting category
public  function checkCate($category, $parent){
  $sql = "SELECT * FROM categories WHERE category = '$category' AND parent =  '$parent' AND deleted = 0 ";
    $this->_db->query($sql);
      if ($this->_db->count()) {
        return $this->_db->first();

      }else{
        return false;
      }

}

public function insertCate($parent, $category){

    $this->_db->insert('categories', array(
      'parent' => $parent,
      'category' => $category
    ));
  return true;
}

//select category parent
public  function fetchCateParent(){
  $val = 0;
  $sql = "SELECT * FROM categories WHERE parent = 0 AND deleted = 0 ORDER BY id";
    $this->_db->query($sql);
      if ($this->_db->count()) {
        $data =  $this->_db->results();
  
     $output = '';
  if ($data) {
      $output .= '
      <table  id="cateTableShow" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Parent</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
      ';

        foreach ($data as $parent) {
       
        $output .= '
        <tr class="bg-primary">
          <td>'.$parent->id.'</td>
          <th>'.ucfirst($parent->category).'</th>
          <th>Parent</th>';
             if (hasPermissionSuper()):
         $output .='
          <th>
            <a href="#"   id="'.$parent->id.'" title="EditCategory" class="text-light editBtn" data-toggle="modal" data-target="#editCategory"><i class="fa fa-edit fa-lg"></i> </a>&nbsp;

              <a href="#" id="'.$parent->id.'" title="Move Category to trash" class="text-danger deleteBtn"><i class="fa fa-recycle fa-lg"></i> </a>
          </th>
        </tr>

        '; 
      endif; 
  
         $sql = "SELECT * FROM categories WHERE parent = '$parent->id' AND deleted = 0 ORDER BY id";
        $cdata = $this->_db->query($sql);
          if ($cdata->count()) {
          $cdatas = $cdata->results();
         foreach ($cdatas as $child) {
          $output .= '
          <tr class="bg-info">
           <th>'.$child->id.'</th>
            <th>'.ucfirst($child->category).'</th>
            <th>'.ucfirst($parent->category).'</th>';
             if (hasPermissionSuper()):
           $output .=' <th>
              <a href="#"  id="'.$child->id.'" title="Edit Category" class="text-light editBtn" data-toggle="modal" data-target="#editCategory"><i class="fa fa-edit fa-lg"></i> </a>&nbsp;

                <a href="#" id="'.$child->id.'" title="Move Category to trash" class="text-danger deleteChildBtn"><i class="fa fa-recycle fa-lg"></i> </a>
            </th>

          </tr>

          ';
          endif; 
        }
      }

      }
      $output .= '
        </tbody>
      </table>
';

  }
  return $output;

  }

}

public function fetchCateParentSelect()
{
    $output = '';
    $val = 0;
  $get = $this->_db->get('categories', array('deleted', '=', $val));
if ($get->count()) {
    $data = $get->results();
  $output .= '<select  name="parent" value="0" id="parent" class="form-control form-control-lg">';
  $output .= '  <option value="0">Parent</option>';
  foreach ($data as $parent) {
    $output .= '<option value="'.$parent->id.'">'.$parent->category.'</option>';
  }
  $output .= '</select>';
}
  return $output;
}

// Move cate to Trash
public function cateAction($val, $id){
  $this->_db->update('categories', 'id', $id, array(
    'deleted' => $val
  ));
  return true;

}


public function cateById($id){
  $sql = "SELECT * FROM categories WHERE  id = '$id' AND deleted = 0";
  $this->_db->query($sql);
 if ($this->_db->count()) {
   return $this->_db->first();
     }else{
        return false;
   }
}


public function cateUpdate($category, $Id){

  $this->_db->update('categories', 'id', $Id, array(
    'category' => $category
  ));
  return true;

}


//checkTag

public function checkTag($tag){


  $data = $this->_db->get('tags', array('tags', '=', $tag));
  if ($data->count()) {
    return true;
  }else{
    return false;
  }
}

//add tags
public function addTag($tags){

    $this->_db->insert('tags', array(
      'tags' => $tags
    ));
  return true;
}


//select all tags
public function fetchTag(){
  $val = 0;
   $tag = $this->_db->get('tags', array('deleted', '=', $val));
  if ($tag->count()) {
    $data = $tag->results();
     $output = '';
  if ($data) {
      $output .= '
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tag</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
      ';

        foreach ($data as $tag) {
        $output .= '
        <tr class="bg-primary">
          <td>'.$tag->id.'</td>
          <th>'.ucfirst($tag->tags).'</th>
          <th>
            <a href="#"   id="'.$tag->id.'" title="EditTag" class="text-light editTagBtn" data-toggle="modal" data-target="#editTag"><i class="fa fa-edit fa-lg"></i> </a>&nbsp;

              <a href="#" id="'.$tag->id.'" title="Move Tag to trash" class="text-danger deleteTagBtn"><i class="fa fa-recycle fa-lg"></i> </a>
          </th>
        </tr>

        ';

      }
      $output .= '
        </tbody>
      </table>

';

  }else{
    $output .= '<h3 class="text-secondary text-center lead">No Tags Yet</h3>';
  }
  return $output;
  }else{
    return false;
  }

}



public function fetchBookbyCategoryparent()
{
  $sql = "SELECT * FROM categories WHERE parent = 0 AND deleted = 0 ";
  $catbooks = $this->_db->query($sql);
  if ($catbooks->count()) {
    return $catbooks->results();
  }else{
    return 0;
  }
}

public function fetchBookbyCategorychild($parentId)
{
  $sql = "SELECT * FROM categories WHERE parent = '$parentId'";
   $catbooks = $this->_db->query($sql);
  if ($catbooks->count()) {
    return $catbooks->results();
  }else{
    return 0;
  }
}








}//end of class
