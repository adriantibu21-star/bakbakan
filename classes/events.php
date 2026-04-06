<?php
require_once('../config.php');

$game_id = $_GET['game_id'];

//display balance
$qry = $conn->query("SELECT name,description,event_img FROM `events` where game_id = '{$game_id}' and active = 'Y' limit 1");


if ($qry->num_rows > 0) {
   $row = $qry->fetch_assoc();
   $response = array('name' => $row['name'], 'description' =>  $row['description'], 'event_img' =>  $row['event_img']);
   echo json_encode($response);
} else {
   $response = array('name' => "NO EVENT", 'description' => "-");
   echo json_encode($response);
}
