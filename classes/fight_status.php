<?php 
require_once('../config.php');
$eventid = $_GET['eventid'];
$stat = $conn->query("SELECT status,drawno FROM `draws` where eventid = '{$eventid}' and active = 'Y' order by id desc limit 1 ");
if ($stat->num_rows >0){
      $row = $stat->fetch_assoc();
            if ($row['status']=='1'){
                echo   '<span class="ml-6 badge badge-success">OPEN</span>';
            }elseif ($row['status']=='2'){
				echo   '<span class="ml-6 badge badge-success">OPEN</span>';
            }else{
				echo   '<span class="ml-6 badge badge-danger">CLOSED</span>';
            }


                            
}else{
    echo   '<span class="ml-6 badge badge-danger">CLOSED</span>';
}
?>
