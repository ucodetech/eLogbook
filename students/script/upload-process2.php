<?php
require_once '../../core/init.php';
$user = new User();
$fileupload = new FileUpload();
$show = new Show();
$error = array();
$user_id = $user->data()->stu_id;
$db = Database::getInstance();

$file = Input::get('signature_file');

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
$file_path = $fileupload->moveFile($temp_file, "students","signature", $filename)->path();
$db_path = $file_path;
$date = date('Y-m-d');
$Update = "UPDATE students SET signature = '$db_path' WHERE stu_id = '$user_id' ";
if ($db->query($Update))
    echo 'success';
