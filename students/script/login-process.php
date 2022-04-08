<?php
require_once '../../core/init.php';

$user = new User();
$validate = new Validate();
$show = new Show();

if (isset($_POST['action']) && $_POST['action'] == 'loginStudent') {

    $regNo = $show->test_input($_POST['stud_regNo']);
    $password = $show->test_input($_POST['password']);

    if (empty($_POST['stud_regNo'])) {
        echo $show->showMessage('danger','Reg No is required', 'warning');
        return false;
    }
    if (empty($_POST['password'])) {
        echo $show->showMessage('danger','Password is required', 'warning');
        return false;
    }


    $login = $user->login($regNo, $password);
    if ($login) {
        echo 'success';
    }


}
