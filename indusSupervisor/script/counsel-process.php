<?php
require_once '../../core/init.php';

$counsel = new Counsel();
$show = new Show();
$validate = new Validate();

if (isset($_POST['action']) && $_POST['action'] == 'submitCounsel'){
   if (Input::exists()){
       $validation = $validate->check($_POST, array(

           'surname' => array(
               'required' => true
           ),
           'othernames'  => array(
               'required' => true
           ),
           'sex' => array(
               'required' => true
           ),
           'age'  => array(
               'required' => true
           ),
           'address' => array(
               'required' => true
           ),
           'department'  => array(
               'required' => true
           ),
           'level' => array(
               'required' => true
           ),
           'phoneNo' => array(
               'required' => true
           ),
           'yourDecision' => array(
               'required' => true
           ),
           'altarCall' => array(
               'required' => true
           ),
           'duration'  => array(
               'required' => true
           ),
           'solution'  => array(
               'required' => true
           ),
           'followUp'  => array(
               'required' => true
           ),
           'yourTime'  => array(
               'required' => true
           ),
           'signature'  => array(
               'required' => true
           ),
           'user_id'  => array(
               'required' => true,
            'unique' => 'counsellingForm'
            ),

       ));
       if ($validation->passed()){
           try {
               $counsel->create('counsellingForm',array(
                   'user_id' => Input::get('user_id'),
                   'surname' => Input::get('surname'),
                   'othernames' => Input::get('othernames'),
                   'sex' => Input::get('sex'),
                   'age' => Input::get('age'),
                   'address' => Input::get('address'),
                   'department' => Input::get('department'),
                   'level' => Input::get('level'),
                   'phoneNo' => Input::get('phoneNo'),
                   'yourDecison' => Input::get('yourDecision'),
                   'altarCallAnswer' => Input::get('altarCall'),
                   'problemDuration' => Input::get('duration'),
                   'yourSolution' => Input::get('solution'),
                   'counsellorsfollowUp' => Input::get('followUp'),
                   'yourTime' => Input::get('yourTime'),
                   'signature' => Input::get('signature')
               ));
              echo  'success';
           }catch (Exception $e){
               echo $show->showMessage('danger', $e->getMessage(), 'warning');
               echo $show->showMessage('danger', 'Code Line '.$e->getLine(), 'warning');
               echo $show->showMessage('danger', 'Code ' .$e->getCode(), 'warning');
               return false;
           }

       }else{
           foreach ($validation->errors() as $error){
               echo $show->showMessage('danger', $error, 'warning');
               return false;
           }
       }
   }
}
