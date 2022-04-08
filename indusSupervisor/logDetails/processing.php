<!-- certify student logs -->
<?php 

if (isset($_POST['superCertify'])){
    $ceritify = $show->test_input($_POST['ceritify']);
    $date = date('Y-m-d'); 

    $save = $db->query("UPDATE logbookOthers");
}
    
