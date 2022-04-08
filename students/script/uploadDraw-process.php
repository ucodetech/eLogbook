<?php
require_once '../../core/init.php';
$user = new User();
$fileupload = new FileUpload();
$show = new Show();
$error = array();
$user_id = $user->data()->stud_unique_id;
$db = Database::getInstance();

$week = Input::get('week');

if ($user->checkUploaded($user_id, $week)) {

}else{
    echo 'You have uploaded sketches for the month!';
    return false;   
}

$file = Input::get('uploadSketchesFile');
$filename = $file['name'];


if (empty($file['name'])) {
    echo $show->showMessage('danger', 'File cant be empty!', 'warning');
    return false;
}
if (!$fileupload->isImage($filename)) {
    echo $show->showMessage('danger', 'File is not a valid image!', 'warning');
    return false;

}
if ($fileupload->fileSize($filename)) {
    echo $show->showMessage('danger', 'File size is too large!', 'warning');
    return false;
}


$ds = DIRECTORY_SEPARATOR;
$temp_file = $file['tmp_name'];
$file_path = $fileupload->moveFile($temp_file, "students","sketches", $filename)->path();
$db_path = $file_path;
$date = date('Y-m-d');
 
$save = $db->query("UPDATE logbookOthers SET  sketches = '$db_path', uploaded = 1 WHERE stu_unique_id = '$user_id' AND week_number = '$week' ");
if ($save) {
   echo 'success';
}
 