<?php
 require_once '../../core/init.php';

 $certificate = new Certificate();
 $show = new Show();
 $fileupload = new FileUpload();
 $user = new User();

 if (isset($_POST['action']) && $_POST['action'] == 'memberC') {
 	$certs = $certificate->fetchCerti();
 	if ($certs) {
 		foreach ($certs as $cert) {

 			echo '
 				<div class="col-md-4">
 					<a href="../uploads/docs/'.$cert->certificate.'" data-lightbox="'.$cert->certificate.'">
 					<img src="../uploads/docs/'.$cert->certificate.'" class="img-fluid">
 					</a>
 				</div>
 			';
 		}
 	}else{
 		echo '<h3 class="text-danger">No Certificate yet</h3>';
 	}
 }


if (isset($_FILES['certFile']) && $_FILES['certFile']) {

	$userid = $show->test_input($_POST['userid']);


	if ($certificate->checkUser($userid)) {
		echo $show->showMessage('danger', 'Member Already has a certificate!', 'warning');
		return false;
	}

	$file = $_FILES['certFile'];
	$filename = $file['name'];

	if (empty($file['name'])) {
		echo $show->showMessage('danger', 'File Can not be empty!', 'warning');
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
    $file_path = $fileupload->moveFile($temp_file, "uploads","docs", $filename)->path();

   	try {
   		 $certificate->create(array(
    	'user_id' => $userid,
    	'certificate' => $file_path
    ));

   echo $show->showMessage('success', 'Upload Successful!', 'check');
   	} catch (Exception $e) {
   		echo $show->showMessage('danger', $e->getMessage(), 'warning');
   		return false;
   	}

}
