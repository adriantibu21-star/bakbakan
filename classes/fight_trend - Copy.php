<?php require_once('../config.php'); ?>
<?php 

$game_id = $_GET['game_id'];

    $eventqry = $conn->query("SELECT id from events where game_id = '{$game_id}' and active ='Y' order by id desc limit 1 ");
    if($eventqry->num_rows > 0){
        $erow = $eventqry->fetch_assoc();
        $eventid = $erow['id'];
    }else{
        $eventid=0;
    }
?>
<style>
.circle {
  display: inline-table;
  vertical-align: middle;
  width: 28px;
  height: 28px;
  
  background-color: #bbb;
  border-radius: 50%;
}

.circle2 {
  display: inline-table;
  vertical-align: middle;
  width: 40px;
  height: 40px;
  
  background-color: #bbb;
  border-radius: 50%;
}
.circle_content {
  display: table-cell;
  vertical-align: middle;
  text-align: center;
  color: white;
  font-weight: bold;
}
.scrollit {
    overflow-x:scroll;
    height:100%;   
}
.dot {
vertical-align: middle;
padding:13px;
  height: 20px;
  width: 20px;
  background-color: white;
  border-radius: 50%;
  display: inline-block;
}

         .btn-circle-red {
             background-image: linear-gradient(to right, #f34141 0%, #f34141 51%, #fc1809 100%);

          }

         .btn-circle-blue {
             background-image: linear-gradient(to right, #1f82ea 0%, #1f82ea 51%, #207ff1 100%);
          }

         .btn-circle-green {
            background-image: linear-gradient(to right, #25b04b 0%, #25b04b  51%, #25b04b 100%);
          }

         .btn-circle-silver {
            background-image: linear-gradient(to right, #75767a 0%, #75767a 51%, #777978 100%);
          }

</style>


<!-- ito ung galing sa trende-->
<?php 
                            $i = 0;
                            $counter =0;
                            $old = 0;
                            $row1L=0;
                            $row2L=0;
                            $row3L=0;
                            $row4L=0;
                            $row5L=0;
                            $row6L=0;
                            $row7L=0;
                            $row1 = '<tr>';
                            $row2 = '<tr>';
                            $row3 = '<tr>';
                            $row4 = '<tr>';
                            $row5 = '<tr>';
                            $row6 = '<tr>';
                            $row7 = '<tr>';
                            $x = '<td>&nbsp</td>';
                            $group=0;
								$qry = $conn->query("SELECT * from draws where active='N' and eventid = '{$eventid}' order by id ASC");
								while($row = $qry->fetch_assoc()){

                                    if($row['winner']=='1'){
                                        $group=1;
                                    }elseif($row['winner']=='2'){
                                        $group=2;
                                    }else{
                                        $group=$old;
                                    }

                                    if($old !== $group and $counter !== 0){
                                        $counter=0;
                                    }

                                    //use col para auto add ng <td>
                                        if($counter == 0){
                                            $row1L++;
                                            if($row['winner'] == '1'){
                                                $row1.='<td ><span class="circle btn-circle-red "><div class="circle_content"></div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row1.='<td ><span class="circle btn-circle-blue "><div class="circle_content"></div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row1.='<td ><span class="circle btn-circle-green "><div class="circle_content"></div></span></td>';
                                            }else{
                                                $row1.='<td ><span class="circle btn-circle-silver "><div class="circle_content"></div></span></td>';
                                            }
                                           
                                        }elseif($counter == 1){
                                            $row2L++;
                                            if($row['winner'] == '1'){
                                                $row2.=str_repeat($x,$row1L- $row2L).'<td ><span class="circle btn-circle-red "><div class="circle_content"></div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row2.=str_repeat($x,$row1L- $row2L).'<td ><span class="circle btn-circle-blue "><div class="circle_content"></div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row2.=str_repeat($x,$row1L- $row2L).'<td ><span class="circle btn-circle-green "><div class="circle_content"></div></span></td>';
                                            }else{
                                                $row2.=str_repeat($x,$row1L- $row2L).'<td ><span class="circle btn-circle-silver "><div class="circle_content"></div></span></td>';
                                            }
                                            $row2L=$row1L;
                                        }elseif($counter == 2){
                                            $row3L++;
                                            if($row['winner'] == '1'){
                                                $row3.=str_repeat($x,$row1L- $row3L).'<td ><span class="circle btn-circle-red "><div class="circle_content"></div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row3.=str_repeat($x,$row1L- $row3L).'<td ><span class="circle btn-circle-blue "><div class="circle_content"></div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row3.=str_repeat($x,$row1L- $row3L).'<td ><span class="circle btn-circle-green "><div class="circle_content"></div></span></td>';
                                            }else{
                                                $row3.=str_repeat($x,$row1L- $row3L).'<td ><span class="circle btn-circle-silver "><div class="circle_content"></div></span></td>';
                                            }
                                            $row3L=$row1L;
                                        }elseif($counter == 3){
                                            $row4L++;
                                            if($row['winner'] == '1'){
                                                $row4.=str_repeat($x,$row1L- $row4L).'<td ><span class="circle btn-circle-red "><div class="circle_content"></span></div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row4.=str_repeat($x,$row1L- $row4L).'<td ><span class="circle btn-circle-blue "><div class="circle_content"></span></div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row4.=str_repeat($x,$row1L- $row4L).'<td ><span class="circle btn-circle-green "><div class="circle_content"></span></div></span></td>';
                                            }else{
                                                $row4.=str_repeat($x,$row1L- $row4L).'<td ><span class="circle btn-circle-silver "><div class="circle_content"></span></div></span></td>';
                                            }
                                            $row4L=$row1L;
                                        }elseif($counter == 4){
                                            $row5L++;
                                            if($row['winner'] == '1'){
                                                $row5.=str_repeat($x,$row1L- $row5L).'<td ><span class="circle btn-circle-red "><div class="circle_content"></span></div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row5.=str_repeat($x,$row1L- $row5L).'<td ><span class="circle btn-circle-blue "><div class="circle_content"></span></div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row5.=str_repeat($x,$row1L- $row5L).'<td ><span class="circle btn-circle-green "><div class="circle_content"></span></div></span></td>';
                                            }else{
                                                $row5.=str_repeat($x,$row1L- $row5L).'<td ><span class="circle btn-circle-silver "><div class="circle_content"></span></div></span></td>';
                                            }
                                            $row5L=$row1L;
                                        }elseif($counter == 5){
                                            $row6L++;
                                            if($row['winner'] == '1'){
                                                $row6.=str_repeat($x,$row1L- $row6L).'<td ><span class="circle btn-circle-red "><div class="circle_content"></span></div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row6.=str_repeat($x,$row1L- $row6L).'<td ><span class="circle btn-circle-blue "><div class="circle_content"></span></div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row6.=str_repeat($x,$row1L- $row6L).'<td ><span class="circle btn-circle-green "><div class="circle_content"></span></div></span></td>';
                                            }else{
                                                $row6.=str_repeat($x,$row1L- $row6L).'<td ><span class="circle btn-circle-silver "><div class="circle_content"></span></div></span></td>';
                                            }
                                            $row6L=$row1L;
                                        }else{
                                            $row7L++;
                                            if($row7L>$row1L){
                                                $row7L=$row1L;
                                            }
                                            if($row['winner'] == '1'){
                                                $row7.=str_repeat($x,$row1L- $row7L).'<td ><span class="circle btn-circle-red "><div class="circle_content"></span></div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row7.=str_repeat($x,$row1L- $row7L).'<td ><span class="circle btn-circle-blue "><div class="circle_content"></span></div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row7.=str_repeat($x,$row1L- $row7L).'<td ><span class="circle btn-circle-green "><div class="circle_content"></span></div></span></td>';
                                            }else{
                                                $row7.=str_repeat($x,$row1L- $row7L).'<td ><span class="circle btn-circle-silver "><div class="circle_content"></span></div></span></td>';
                                            }
                                            $row7L=$row1L;
                                        }
                                    $i++;
                                    $counter++;
                                    $old = $group;
                                } 
                                $row1.='</tr>';
                                $row2.='</tr>';
                                $row3.='</tr>';
                                $row4.='</tr>';
                                $row5.='</tr>';
                                $row6.='</tr>';
                                $row7.='</tr>';
?>


<table class="table-responsive" style="width: 100%; height:260px; background-image: url('gridcircle2.png'); background-attachment: local; >
				<tbody>
                    <?php echo $row1 ?>
                    <?php echo $row2 ?>
                    <?php echo $row3 ?>
                    <?php echo $row4 ?>
                    <?php echo $row5 ?>
                    <?php echo $row6 ?>
                    <?php echo $row7 ?>
				</tbody>
</table>

<!-- ito ung galing sa trende-->


<div class="row">
        <div class="col p-0 text-center pt-0">
        <span class="circle2 btn-circle-red ">
            <div class="circle_content">
            <?php 
                $meron = $conn->query("SELECT id FROM `draws` where winner =1 and eventid = '{$eventid}' ")->num_rows;
                echo number_format($meron);
            ?>	
            </div>
        </span>
        </div>

        <div class="col p-0 text-center pt-0">
        <span class="circle2 btn-circle-blue ">
            <div class="circle_content">
            <?php 
                $wala = $conn->query("SELECT id FROM `draws` where winner =2 and eventid = '{$eventid}' ")->num_rows;
                echo number_format($wala);
            ?>	
            </div>
        </span>
        </div>

        <div class="col p-0 text-center pt-0">
        <span class="circle2 btn-circle-green">
            <div class="circle_content">
            <?php 
                $draw = $conn->query("SELECT id FROM `draws` where winner =3 and eventid = '{$eventid}' ")->num_rows;
                echo number_format($draw);
            ?>	
            </div>
        </span>
        </div>

        <div class="col p-0 text-center pt-0">        
        <span class="circle2 btn-circle-silver ">
            <div class="circle_content">
            <?php 
                $cancel = $conn->query("SELECT id FROM `draws` where winner =4 and eventid = '{$eventid}' ")->num_rows;
                echo number_format($cancel);
            ?>
            </div>	
        </span>
        </div>
</div>

<div class="row">
    <div class="col p-0 text-center pt-0" style="color:white">
    MERON
    </div>
    <div class="col p-0 text-center pt-0" style="color:white">
    WALA
    </div>
    <div class="col p-0 text-center pt-0" style="color:white">
    DRAW
    </div>
    <div class="col p-0 text-center pt-0" style="color:white">
    CANCELLED
    </div>
</div>
  
<?php 
                            $i = 0;
                            $counter =0;
                            $row1 = '<tr>';
                            $row2 = '<tr>';
                            $row3 = '<tr>';
                            $row4 = '<tr>';
                            $row5 = '<tr>';
			    $row6 = '<tr>';
			    $row7 = '<tr>';
								$qry = $conn->query("SELECT winner, drawno from draws where active='N' and eventid = '{$eventid}' order by id ASC");
								while($row = $qry->fetch_assoc()){
                                    if($i % 6 == 0){ //$i % 6 == 0
                                        $counter=0;
                                    }
                                        if($counter == 0){

                                            if($row['winner'] == '1'){
                                                $row1.='<td><span class="circle btn-circle-red "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row1.='<td><span class="circle btn-circle-blue "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row1.='<td><span class="circle btn-circle-green "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }else{
                                                $row1.='<td><span class="circle btn-circle-silver "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }


                                            
                                        }elseif($counter == 1){
                                            if($row['winner'] == '1'){
                                                $row2.='<td><span class="circle btn-circle-red "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row2.='<td><span class="circle btn-circle-blue "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row2.='<td><span class="circle btn-circle-green "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }else{
                                                $row2.='<td><span class="circle btn-circle-silver "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }

                                        }elseif($counter == 2){

                                            if($row['winner'] == '1'){
                                                $row3.='<td><span class="circle btn-circle-red "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row3.='<td><span class="circle btn-circle-blue "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row3.='<td><span class="circle btn-circle-green "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }else{
                                                $row3.='<td><span class="circle btn-circle-silver "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }

                                        }elseif($counter == 3){
                                            if($row['winner'] == '1'){
                                                $row4.='<td><span class="circle btn-circle-red "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row4.='<td><span class="circle btn-circle-blue "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row4.='<td><span class="circle btn-circle-green "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }else{
                                                $row4.='<td "><span class="circle btn-circle-silver "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }
                                        }elseif($counter == 4){
                                            if($row['winner'] == '1'){
                                                $row5.='<td><span class="circle btn-circle-red "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row5.='<td><span class="circle btn-circle-blue "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row5.='<td><span class="circle btn-circle-green "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }else{
                                                $row5.='<td><span class="circle btn-circle-silver "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }
                                        }elseif($counter == 5){
                                            if($row['winner'] == '1'){
                                                $row6.='<td><span class="circle btn-circle-red "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row6.='<td><span class="circle btn-circle-blue "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row6.='<td><span class="circle btn-circle-green "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }else{
                                                $row6.='<td><span class="circle btn-circle-silver "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }
                                        }else{
                                            if($row['winner'] == '1'){
                                                $row7.='<td><span class="circle btn-circle-red "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row7.='<td><span class="circle btn-circle-blue "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row7.='<td><span class="circle btn-circle-green "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }else{
                                                $row7.='<td><span class="circle btn-circle-silver "><div class="circle_content">'.$row['drawno'].'</span></div></span></td>';
                                            }
                                        }
                                    $i++;
                                    $counter++;
                                } 
                                $row1.='</tr>';
                                $row2.='</tr>';
                                $row3.='</tr>';
                                $row4.='</tr>';
                                $row5.='</tr>';
                                $row6.='</tr>';
				$row7.='</tr>';
?>

<table class="table-responsive" style="width: 100%; height:240px; background-image: url('gridcircle.png'); background-attachment: local, scroll;>
				<tbody>
                    <?php echo $row1 ?>
                    <?php echo $row2 ?>
                    <?php echo $row3 ?>
                    <?php echo $row4 ?>
                    <?php echo $row5 ?>
                    <?php echo $row6 ?>
                    <?php echo $row7 ?>
				</tbody>
</table>  