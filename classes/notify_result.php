<?php 
require_once('../config.php');
//display winner
$winner = $conn->query("SELECT winner FROM `draws` order by id desc limit 1 "); //pinaka last na draw
if ($winner->num_rows >0){
      $row = $winner->fetch_assoc();
      echo $row['winner'];
}else{
      echo 0;
}
?>

