<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <?php
  $title = basename($_SERVER['PHP_SELF'], '.php');
  $title = explode('-', $title);
  $title = ucfirst($title[1]);
  $admin = new Admin();
  ?>
  <title><?php echo $title; ?>-<?php echo SITENAME; ?></title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="<?=URLROOT?>assets/css/material-dashboard.css?v=2.1.0" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="<?=URLROOT?>assets/demo/demo.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?=URLROOT?>assets/master.css">
</head>
<style>
  .studentProfile{
    width: 100px !important;
    height: 100px !important;
    border-radius: 50px;
    border: 2px double #9623ad;
  }
  .hr2{
    background: #8832a0;
    color: #8832a0;
    width: 100%;
    height: 10px;
    border-radius: 30px;
  }

</style>
<body class="dark-edition">
  <div class="wrapper ">
    <div class="sidebar" data-color="purple" data-background-color="black" data-image="<?=URLROOT?>assets/img/sidebar-2.jpg">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo"><a href="<?=URLROOT?>" class="simple-text logo-normal">
        <img src="<?=URLROOT?>admin/profile/<?=$admin->data()->admin_passport;?>" class="img img-fluid studentProfile" alt="<?=$admin->data()->admin_fullname?>">
        </a>
        <h5 class="text-center text-muted">
          <?= strtok($admin->data()->admin_fullname, ' ') . ' => '. strtok($admin->data()->admin_permissions, ',')?>
          <hr>
          <a href="logout" class="text-danger">Logout</a>
        </h5>
        <?php if (Session::exists('denied')): ?>
           <div class="alert alert-danger alert-dismissible">
               <button type="button" class="close" data-dismiss="alert">
              &times;
              </button>
       <i class="fa fa-warning"></i>&nbsp;
               <strong class="text-left">
                   <?=Session::flash('denied') ?>
               </strong>
           </div>
       <?php endif ?>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item active  ">
            <a class="nav-link" href="admin-dashboard">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="admin-profile">
              <i class="material-icons">person</i>
              <p>User Profile</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="admin-admins">
              <i class="material-icons fa fa-user-secret"></i>
              <p>Admins</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="admin-students">
              <i class="material-icons fa fa-users"></i>
              <p>Students</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="admin-supervisors">
              <i class="material-icons fa fa-users"></i>
              <p>Supervisors</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="admin-logbook">
              <i class="material-icons fa fa-book"></i>
              <p>Log Books</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="admin-notifications">
              <i class="material-icons">notifications</i>
              <p>Notifications</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="admin-settings">
              <i class="material-icons fa fa-gear"></i>
              <p>Settings</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="main-panel">
