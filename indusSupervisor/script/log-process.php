<?php 
require_once '../../core/init.php';

$logbook = new Logbook();
$user = new User();
$validate = new Validate();
$show = new Show();
$uniqueid = $user->data()->stud_unique_id;
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