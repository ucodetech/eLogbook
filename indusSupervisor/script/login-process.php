<?php
require_once '../../core/init.php';

$inds = new Supervisor();
$validate = new Validate();
$show = new Show();

if (isset($_POST['action']) && $_POST['action'] == 'loginInds') {

    $comp_email = $show->test_input($_POST['company_email']);
    $password = $show->test_input($_POST['password']);

    if (empty($_POST['company_email'])) {
        echo $show->showMessage('danger','Company Email is required', 'warning');
        return false;
    }
    if (empty($_POST['password'])) {
        echo $show->showMessage('danger','Password is required', 'warning');
        return false;
    }


    $login = $inds->login($comp_email, $password);
    if ($login) {
        echo 'success';
    }


}
