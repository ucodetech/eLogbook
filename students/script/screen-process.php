<?php
require_once '../../core/init.php';

$screen = new Counsel();
$show = new Show();
$validate = new Validate();

if (isset($_POST['action']) && $_POST['action'] == 'submitScreen'){

    if (Input::exists()){
        $validation = $validate->check($_POST, array(
            'surname' =>  array(
                'required' => true,
            ),
            'othernames' =>  array(
                'required' => true,
            ),
            'phoneNo' =>  array(
                'required' => true,
            ),
            'dob' =>  array(
                'required' => true,
            ),
            'maritalStatus' =>  array(
                'required' => true,
            ),
            'department' =>  array(
                'required' => true,
            ),
            'level' =>  array(
                'required' => true,
            ),
            'cgpa' =>  array(
                'required' => true,
            ),
            'bornAgain' =>  array(
                'required' => true,
            ),
            'bornAgainDate' =>  array(
                'required' => false,
            ),
            'howItHappened' =>  array(
                'required' => false,
            ),
            'homeChurch' =>  array(
                'required' => true,
            ),
            'christianLeadership' =>  array(
                'required' => true,
            ),
            'whereDid' =>  array(
                'required' => false,
            ),
            'positionHeld' =>  array(
                'required' => false,
            ),
            'spiritualGift' =>  array(
                'required' => true,
            ),
            'brieflyDesribe' =>  array(
                'required' => false,
            ),
            'signature' =>  array(
                'required' => true,
            ),
            'user_id' =>  array(
                'required' => true,
                'unique' => 'screeningForm'
            ),

        ));

        if ($validation->passed()){
            try {
                $screen->create('screeningForm',array(
                    'user_id' => Input::get('user_id'),
                    'surname' => Input::get('surname'),
                    'othernames' => Input::get('othernames'),
                    'dateOfBirth' => Input::get('dob'),
                    'maritalStatus' => Input::get('maritalStatus'),
                    'cgpa' => Input::get('cgpa'),
                    'department' => Input::get('department'),
                    'level' => Input::get('level'),
                    'phoneNo' => Input::get('phoneNo'),
                    'bornAgain' => Input::get('bornAgain'),
                    'dateBornAgain' => Input::get('bornAgainDate'),
                    'howItHappened' => Input::get('howItHappened'),
                    'yourHomeChurch' => Input::get('homeChurch'),
                    'christianLeadership' => Input::get('christianLeadership'),
                    'whereDidYou' => Input::get('whereDid'),
                    'positonHeld' => Input::get('positionHeld'),
                    'discoveredGift' => Input::get('spiritualGift'),
                    'describeBriefly' => Input::get('brieflyDesribe'),
                    'signature' => Input::get('signature'),
                ));
                echo 'success';
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