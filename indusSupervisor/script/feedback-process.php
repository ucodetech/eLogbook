<?php
require_once  '../../core/init.php';
$user = new User();
$feedback = new Feedback();
$show = new Show();
$validate = new Validate();

if (isset($_POST['action']) && $_POST['action'] == 'sendFeedback') {
    if (Input::exists()) {
        $validation = $validate->check($_POST, array(
            'user_id' => array(
                'required' => true,
            ),
            'Fullname' => array(
                'required' => true,
            ),
            'subject' => array(
                'required' => true,
            ),
            'feedback' => array(
                'required' => true,
            ),

        ));
        if ($validation->passed()) {
            $userid = Input::get('user_id');
            try {
                $feedback->feedback(array(
                    'user_id' => Input::get('user_id'),
                    'subject' => Input::get('subject'),
                    'feedback' => Input::get('feedback')
                ));
                $feedback->notifyMe($userid, 'Sent Feedback');
                echo 'success';

            } catch (Exception $e) {
                echo $show->showMessage('danger', $e->getMessage(), 'warning');
                return false;
            }

        } else {
            foreach ($validation->errors() as $error) {
                echo $show->showMessage('danger', $error, 'warning');
                return false;
            }
        }

    }
}
