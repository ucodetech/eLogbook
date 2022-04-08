<?php
require_once '../../core/init.php';
$admin = new Admin();
$show = new Show();

// fetch admins
if (isset($_POST['action']) && $_POST['action']== 'fatchAdmins') {
  $data = $admin->grabAdmin();
  if ($data) {
    echo $data;
  }
}
