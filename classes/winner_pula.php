<?php 
require_once('../config.php');

$eventid = $_GET['eventid'];

	  $winner = $conn->query("SELECT winner, drawno FROM `draws` where eventid = '{$eventid}' order by id desc limit 1 "); //pinaka last na draw
		if ($winner->num_rows >0){
      	$rows = $winner->fetch_assoc();
      			if($rows['winner'] ==1){
					echo   'PULA <small class="blinking" >WINNER</small>';
                }elseif($rows['winner'] ==3){
                    echo   'PULA <small class="blinking">DRAW</small>';
                }elseif($rows['winner'] ==4){
                    echo   'PULA <small class="blinking">CANCELLED</small>';
				}else{
                    echo   'PULA';
                }
		}else{
            echo   'PULA';
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

