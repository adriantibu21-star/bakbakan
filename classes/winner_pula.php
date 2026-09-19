<?php 
require_once('../config.php');

$eventid = $_GET['eventid'];

	  $winner = $conn->query("SELECT d.winner, d.drawno, e.meron_text FROM `draws` d INNER JOIN events e on e.id = d.eventid where d.eventid = '{$eventid}' order by d.id desc limit 1 "); //pinaka last na draw
		if ($winner->num_rows >0){
      	$rows = $winner->fetch_assoc();
      			if($rows['winner'] ==1){
					echo   $rows['meron_text'] . ' <small class="blinking" ></br>WINNER</small>';
                }elseif($rows['winner'] ==3){
                    echo   $rows['meron_text'] . ' <small class="blinking"></br>DRAW</small>';
                }elseif($rows['winner'] ==4){
                    echo   $rows['meron_text'] . ' <small class="blinking"></br>CANCELLED</small>';
				}else{
                    echo   $rows['meron_text'];
                }
		}else{
            echo   'MERON';
        }
?>
<style>
    .blinking{
    animation:blinkingText 1.5s infinite;
}
@keyframes blinkingText{
    0%{     color: #FFF;    }
    49%{    color: #FFF; }
    60%{    color: transparent; }
    99%{    color:transparent;  }
    100%{   color: #FFF;    }
}

</style>

