<?php 
require_once('../config.php');
function getBalance()
{
//display balance
$balance = $conn->query("SELECT amount FROM `users` where id = '{$_settings->userdata('id')}' ");
$bal=0;
if ($balance->num_rows >0){
      $row = $balance->fetch_assoc();
      $bal= number_format($row['amount'],2);                           
}

      return $bal;
}
?>