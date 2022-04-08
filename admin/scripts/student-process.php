<?php
require_once '../../core/init.php';

$general = new General();
$show = new Show();
$student = new Student();



if (isset($_POST['action']) && $_POST['action'] == 'fetch_students') {

	$fetchStudent = $student->fetchStudents();
	if ($fetchStudent) {
		echo $fetchStudent;
	}

}


if (isset($_POST['student_id'])) {
	$student_id = (int)$_POST['student_id'];

	$get = $student->getStudentDetail($student_id);
	if ($get) {
		echo $get;
	}
}
