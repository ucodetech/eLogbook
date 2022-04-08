<?php 
require_once '../../core/init.php';

$logbook = new Logbook();
$user = new User();
$validate = new Validate();
$show = new Show();
$uniqueid = $user->data()->stud_unique_id;
$db = Database::getInstance();

// add placement information
if (isset($_POST['action']) && $_POST['action'] == 'addPlacement') {
    

    if (Input::exists()) {
        $validation = $validate->check($_POST, array(
            'nameOfEstablishment' => array(
                'required' => true    
            ),
             'location' => array(
                'required' => true    
            ),
              'yearOperationStated' => array(
                'required' => true    
            ),
               'principleAreaOperation' => array(
                'required' => true    
            ),
             'employmentSize' => array(
                'required' => true    
            ),
             'city' => array(
                'required' => true
             )
            
        ));

        if ($validation->passed()){
            try {
                 $logbook->create('placementInfo', array(
                    'stud_unique_id' => $uniqueid, 
                    'nameOfEst' => Input::get('nameOfEstablishment'), 
                    'location' => Input::get('location'),  
                    'yearOpStarted' => Input::get('yearOperationStated'), 
                    'prinAreaOp' => Input::get('principleAreaOperation'),
                    'city' => Input::get('city'),
                    'prod_undertaken' => Input::get('prod_undertaken'), 
                    'employmentSize' => Input::get('employmentSize')  
             )); 
                 echo 'success';
            } catch (Exception $e) {
                echo $show->showMessage('danger', $e->getMessage(), 'warning');
                return false;
            }
           

        }else{
            foreach ($validation->errors() as $error) {
                echo $show->showMessage('danger', $error, 'warning');
                return false;
            }
        }
    }
}


// fill log book
if (isset($_POST['action']) && $_POST['action'] == 'addActivity') {
   
   if (Input::exists()) {
       $validation = $validate->check($_POST, array(
            'actDay' => array(
                'required' => true
            ),
            'activity' => array(
                'required' => true,
                'min' => 20, 
                'max' => 200
            ),
            'weekNumber' => array(
                'required' => true,
                'min' => 1,
                'max' => 1
            )
        ));

       if ($validation->passed()) {
            
            $date = Input::get('actDay');
            $activity = Input::get('activity');
            $week = Input::get('weekNumber');

            if ($logbook->checkEntryDate($date, $uniqueid)) {
                echo $show->showMessage('danger', 'You have already filled today\'s activity!', 'warning');
                return false;
            }
            if ($user->data()->assigned_supervisor_unid == '' || $user->data()->ind_supervisor_unid == '') {
                echo $show->showMessage('danger', 'You do not have a school based supervisor or an industrial base supervisor assigned to you yet! please contact swies coordinator!', 'warning');
                return false;
            }
            // $sql = "INSERT INTO logbook (stu_unique_id, week_number, log_month, activity) VALUES ('$uniqueid', '$week', '$date','$activity')";
            // if ($db->query($sql))
            //     echo 'success';
            try {
                $logbook->create('logbook', array(
                'stu_unique_id' => $uniqueid,
                'week_number' => $week,
                'log_month' => $date, 
                'activity' => $activity
                ));

            $check = $db->query("SELECT * FROM logbookOthers WHERE stu_unique_id = '$uniqueid' AND week_number = '$week' ");
            if ($check->count()) {
                
            }else{
                try {
                $logbook->create('logbookOthers', array(
                'stu_unique_id' => $uniqueid,
                'week_number' => $week,
                ));
                echo 'success';
            } catch (Exception $e) {
                 echo $show->showMessage('danger', $e->getMessage(), 'warning');
                return false;
            }
        }

            } catch (Exception $e) {
                 echo $show->showMessage('danger', $e->getMessage(), 'warning');
                return false;
            }

       }else{
        foreach ($validation->errors() as $error) {
            echo $show->showMessage('danger', $error, 'warning');
            return false;
        }
       }
   }

}


// fetch log data
if (isset($_POST['action']) && $_POST['action'] == 'fetchLogs') {
    $data = $logbook->fetchlogs($uniqueid);
    if ($data) {
        echo $data;
    }
}