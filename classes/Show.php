<?php
/**
 * post class
 */
class Show
{
  private  $_db;


public function __construct()
  {
    $this->_db = Database::getInstance();
  }



  public function test_input($data){
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlentities($data, ENT_QUOTES, 'UTF-8');
       $data = htmlspecialchars($data);
       $data = strip_tags($data);
       return $data;

     }
   //error message

   public function showMessage($type = 'success', $message, $ico){
  return '
  <div id="" class="alert alert-'.$type.' alert-dismissible">
  <button type="button" class="close" data-dismiss="alert">
           &times;
           </button>
    <i class="fa fa-'.$ico.'"></i>&nbsp;
    <span>'.$message.'</span>
  </div>';
   }

}