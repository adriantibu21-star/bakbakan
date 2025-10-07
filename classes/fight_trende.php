<?php require_once('../config.php'); ?>
<style>
.circle {
  display: inline-table;
  vertical-align: middle;
  width: 32px;
  height: 32px;
  
  background-color: #bbb;
  border-radius: 50%;
}

.circle_content {
  display: table-cell;
  vertical-align: middle;
  text-align: center;
}
.scrollit {
    overflow-x:scroll;
    height:100%;
}
table, th, td{
    border-collapse: collapse;	
}
.center {
  margin-left: auto;
  margin-right: auto;
}
</style>
<div class="row">
        <div class="col p-0 text-center pt-0">
        <span class="circle bg-danger ">
            <div class="circle_content">
            <?php 
                $meron = $conn->query("SELECT id FROM `draws` where winner =1 ")->num_rows;
                echo number_format($meron);
            ?>	
            </div>
        </span>
        </div>

        <div class="col p-0 text-center pt-0">
        <span class="circle bg-primary ">
            <div class="circle_content">
            <?php 
                $wala = $conn->query("SELECT id FROM `draws` where winner =2 ")->num_rows;
                echo number_format($wala);
            ?>	
            </div>
        </span>
        </div>

        <div class="col p-0 text-center pt-0">
        <span class="circle bg-success">
            <div class="circle_content">
            <?php 
                $draw = $conn->query("SELECT id FROM `draws` where winner =3 ")->num_rows;
                echo number_format($draw);
            ?>	
            </div>
        </span>
        </div>

        <div class="col p-0 text-center pt-0">        
        <span class="circle">
            <div class="circle_content">
            <?php 
                $cancel = $conn->query("SELECT id FROM `draws` where winner =4 ")->num_rows;
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
								$qry = $conn->query("SELECT * from draws where active='N'order by id ASC");
								while($row = $qry->fetch_assoc()){
                                    if($i % 5 == 0){ //$i % 5 == 0
                                        $counter=0;
                                    }
                                        if($counter == 0){

                                            if($row['winner'] == '1'){
                                                $row1.='<td "><span class="circle bg-danger "><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row1.='<td "><span class="circle bg-primary "><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row1.='<td "><span class="circle bg-success "><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }else{
                                                $row1.='<td "><span class="circle"><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }


                                            
                                        }elseif($counter == 1){
                                            if($row['winner'] == '1'){
                                                $row2.='<td "><span class="circle bg-danger "><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row2.='<td "><span class="circle bg-primary "><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row2.='<td "><span class="circle bg-success "><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }else{
                                                $row2.='<td "><span class="circle"><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }

                                        }elseif($counter == 2){

                                            if($row['winner'] == '1'){
                                                $row3.='<td "><span class="circle bg-danger "><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row3.='<td "><span class="circle bg-primary "><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row3.='<td "><span class="circle bg-success "><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }else{
                                                $row3.='<td "><span class="circle"><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }

                                        }elseif($counter == 3){
                                            if($row['winner'] == '1'){
                                                $row4.='<td "><span class="circle bg-danger "><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row4.='<td "><span class="circle bg-primary "><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row4.='<td "><span class="circle bg-success "><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }else{
                                                $row4.='<td "><span class="circle"><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }
                                        }else{
                                            if($row['winner'] == '1'){
                                                $row5.='<td "><span class="circle bg-danger "><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }elseif($row['winner'] == '2'){
                                                $row5.='<td "><span class="circle bg-primary "><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }elseif($row['winner'] == '3'){
                                                $row5.='<td "><span class="circle bg-success "><div class="circle_content">'.$row['drawno'].'</div></span></td>';
                                            }else{
                                                $row5.='<td "><span class="circle"><div class="circle_content">'.$row['drawno'].'</div></span></td>';
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
?>

<table class="table-responsive bg-light" style="width: 100%; height:240px;">
				<tbody>
                    <?php echo $row1 ?>
                    <?php echo $row2 ?>
                    <?php echo $row3 ?>
                    <?php echo $row4 ?>
                    <?php echo $row5 ?>
				</tbody>
</table>  