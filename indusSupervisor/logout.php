<?php
require_once  '../core/init.php';
$inds  = new Supervisor();
$inds->logout();
Redirect::to('inds-login');