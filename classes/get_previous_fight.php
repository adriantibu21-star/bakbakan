<?php 
require_once('../config.php');

$eventid = $_GET['eventid'];

$prev_winner = $conn->query("SELECT winner, drawno FROM `draws` where eventid = '{$eventid}' order by id desc limit 1 offset 1"); //second to the last draw
if ($prev_winner->num_rows >0){
    $row = $prev_winner->fetch_assoc();
    $response = array('winner' => $row['winner'], 'prev_fight_no' => $row['drawno'] );
    echo json_encode($response);
}else{
    $response = array('winner' => '', 'prev_fight_no' => '' );
    echo json_encode($response);
}
?>