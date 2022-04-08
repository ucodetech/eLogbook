<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../../core/init.php';
$supervisor = new Supervisor();
$show = new Show();
$validate = new Validate();
$db  = Database::getInstance();


// fetch supervisors
if (isset($_POST['action']) && $_POST['action']== 'fetchStudentsUnderMe') {
  $sup_id = $supervisor->superdata()->unique_id;
  $data = $supervisor->getAnyTableAll('students','ind_supervisor_unid', $sup_id);
  if ($data) {
    $output = '';

    $output .= '<table class="table table-condensed">
  <thead>
    <th>#</th>
    <th>Passport</th>
    <th>Fullname</th>
    <th>Phone No</th>
    <th>Matric No.</th>
    <th>Department</th>
    <th>School</th>
  </thead>
  <tbody>
 
  ';
  foreach ($data as $get) {
    $Passport = '<img src="../students/profile/'.$get->passport.'" alt="'.$get->stud_fname.'" class="activeImg">';
   $output .= '
   <tr>
      <td>'.$get->stu_id.'</td>
      <td>'.$Passport.'</td>
      <td>'.$get->stud_fname. ' '.$get->stud_lname.' '.$get->stud_oname.'</td>
      <td>'.$get->stud_tel.'</td>
      <td>'.$get->stud_regNo.'</td>
      <td>'.$get->stud_dept.'</td>
      <td>'.$get->stud_school.'</td>
     
    </tr>';
  }


$output .='
 </tbody>
</table>';
echo $output;
  }
}


// search for student logbook
if (isset($_POST['action']) && $_POST['action']== 'searchLogbook') {
    $unique_id = $_POST['unique_id'];   


    if (empty($_POST['unique_id'])) {
      echo $show->showMessage('danger', 'Please select the student', 'warning');
        return false;
    }
    
    if ($_POST['search_date'] && !empty($_POST['search_week'])) {
        echo $show->showMessage('danger', 'Search by Full Date only! or By Week Number only ', 'warning');
        return false;
    }


    if (empty($_POST['search_date']) && empty($_POST['search_week'])) {
        echo $show->showMessage('danger', 'Search by Full Date! or By Week Number ', 'warning');
        return false;
    }

     $search_date = (($_POST['search_date'] != '')?$show->test_input($_POST['search_date']): '');
     $search_week = (($_POST['search_week'] != '')?$show->test_input($_POST['search_week']): '');



    //query the database according to user request
    if ($search_date) {
          // $search_date = $_POST['search_date'];    
           $sql = "SELECT * FROM logbook WHERE stu_unique_id = '$unique_id' AND log_month = '$search_date' ";
          $data = Database::getInstance()->query($sql);
          if ($data->count()) {
                $row = $data->first();
               
             echo '<div class="row">
               <div class="col-lg-2 text-left"  style="border:2px solid grey;">
                 <strong class="text-center">
                  '.pretty_dayLetterd($row->log_month).'
                 </strong><br>
                 <span class="text-center">
                 '.pretty_dates($row->log_month).'
               </span>
               </div>
              <div class="col-lg-8 text-left"  style="border:2px solid grey;">
                <stong class="text-left">
                '.$row->activity.'</stong>
              </div>
              <div class="col-lg-2  text-right" style="border:2px solid grey;">
              <u>Week: '.$row->week_number.'</u>
              </div>
            </div>';
          }else{
            echo $show->showMessage('danger','No record yet (by date)','warning');
            return false;
          }

    }elseif($search_week){
    
    // if(isset($_POST['search_week'])) {
         // $search_week  = $_POST['search_week'];
         
         $sql = "SELECT * FROM logbook WHERE stu_unique_id = '$unique_id' AND week_number = '$search_week'";
         $week = Database::getInstance()->query($sql);
         if ($week->count()) {
           $row = $week->first();
            echo '<a href="logDetails/student-logbook.php?log='.$row->stu_unique_id.'&week='.$row->week_number.'">View by week</a>';
          }else{
            echo $show->showMessage('danger','No record yet (week)','warning');
            return false;
          }
      // }

    }
}
