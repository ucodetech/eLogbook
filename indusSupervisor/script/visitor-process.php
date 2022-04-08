<?php
require_once '../../core/init.php';
$user = new User();
$fileupload = new FileUpload();
$show = new Show();
$error = array();
$db = Database::getInstance();
$general = new General();
$file = Input::get('signatureFile');


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
$file_path = $fileupload->moveFile($temp_file, "uploads","signatures", $filename)->path();
$db_path = $file_path;
$date = date('Y-m-d');

    if (Input::exists())
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'fullname' => array(
                'required' => true,
            ),
            'gender' => array(
                'required' => true,
            ),
            'department' => array(
                'required' => false,
            ),
            'level' => array(
                'required' => true,
            ),
            'email' => array(
                'required' => true,
                'unique' => 'chapel_visitors'
            ),
            'address' => array(
                'required' => true,
            ),
            'comment' => array(
                'required' => false,
            ),
            'phoneNo' => array(
                'required' => true,
                'min' => 1,
                'max' => 15,
                'unique' => 'chapel_visitors'
            ),
            'invited_by' => array(
                'required' => false,
            ),
            'prayerRequest' => array(
                'required' => false,
            ),
            'become_member' => array(
                'required' => true,
            )

        )) ;
        if ($validation->passed()) {
            $fileupload->moveToDatabase('chapel_visitors', array(
                'full_name' => Input::get('fullname'),
                'gender' => Input::get('gender'),
                'department' => Input::get('department'),
                'level' => Input::get('level'),
                'email' => Input::get('email'),
                'address' => Input::get('address'),
                'general_comments' => Input::get('comment'),
                'phoneNo' => Input::get('phoneNo'),
                'invited_by' => Input::get('invited_by'),
                'prayer_request' => Input::get('prayerRequest'),
                'become_member' => Input::get('become_member'),
                'signature' => $db_path,
                'signatureDate' => $date
            ));
            echo 'success';
        }else {
            foreach ($validation->errors() as $error) {
                echo $show->showMessage('danger', $error, 'warning');
                return false;
            }
        }