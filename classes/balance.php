<?php 
require_once('../config.php');
//display balance
$balance = $conn->query("SELECT amount FROM `users` where id = '{$_settings->userdata('id')}' ");
if ($balance->num_rows >0){
      $row = $balance->fetch_assoc();
      echo   number_format($row['amount'],2);                           
}
else{
      echo '0.00';
}

?>

