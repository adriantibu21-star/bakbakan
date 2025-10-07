<?php 

require_once('../config.php');
$check = 0;
if ( is_null  ($_settings->userdata('id'))  ){
    $check =0;
}else{
    $check =1;
}

echo $check;
?>



