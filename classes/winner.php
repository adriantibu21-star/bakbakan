<?php 
require_once('../config.php');
	  $winner = $conn->query("SELECT winner, drawno FROM `draws` order by id desc limit 1 "); //pinaka last na draw
		if ($winner->num_rows >0){
      	$rows = $winner->fetch_assoc();
      			if($rows['winner'] ==1){
					echo   '<h5 class="blinking">MERON WINNER</h5>';
				}else{
                    echo   '<h5>MERON</h5>';
                }
		}else{
            echo   '<h5>MERON</h5>';
        }
?>
<style>
    .blinking{
    animation:blinkingText 1.2s infinite;
}
@keyframes blinkingText{
    0%{     color: #FFF;    }
    49%{    color: #FFF; }
    60%{    color: transparent; }
    99%{    color:transparent;  }
    100%{   color: #FFF;    }
}

</style>

