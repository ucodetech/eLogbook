<!-- certify student logs -->
<?php 

if (isset($_POST['superCertify'])){
    $ceritify = $show->test_input($_POST['ceritify']);
    $student = $show->test_input($_POST['student']);
    $week = $show->test_input($_POST['week']);


    $date = date('Y-m-d'); 

    $save = $db->query("UPDATE logbookOthers SET certifiedBy = '$certify', certifiedDate = '$date' WHERE stu_unique_id = '$student' AND week_number = '$week' ");
    if ($save) {
        Session::flash('saved', 'You have successfully certified this student', 'check');

    }
}
    
