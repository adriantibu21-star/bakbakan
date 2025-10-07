<?php require_once('../config.php'); ?>
<?php 
                            $i = 0;
                            $counter =0;
                            $old = 0;
                            $row1L=0;
                            $row2L=0;
                            $row3L=0;
                            $row4L=0;
                            $row5L=0;
                            $row1 = '<tr>';
                            $row2 = '<tr>';
                            $row3 = '<tr>';
                            $row4 = '<tr>';
                            $row5 = '<tr>';
                            $x = '<td>&nbsp</td>';
                            $group=0;
								$qry = $conn->query("SELECT * from draws where active='N'order by id ASC");
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
                                       
                                    
                                        if($counter == 0){
                                            $row1L++;
                                            $row1.='<td>'.$row['winner'].'</td>';
                                            
                                        }elseif($counter == 1){
                                            $row2L++;
                                            $row2.= str_repeat($x,$row1L- $row2L).'<td>'.$row['winner'].'</td>';
                                            //update new $row2L
                                            $row2L=$row1L;

                                            
                                        }elseif($counter == 2){
                                            $row3L++; 
                                            $row3.=str_repeat($x,$row1L- $row3L).'<td>'.$row['winner'].'</td>';   
                                            $row3L=$row1L;
                                        }elseif($counter == 3){
                                            $row4L++;
                                            $row4.=str_repeat($x,$row1L- $row4L).'<td>'.$row['winner'].'</td>';
                                            $row4L=$row1L;
                                        }else{
                                            $row5L++;
                                            if($row5L>$row1L){
                                                $row5L=$row1L;
                                            }
                                            $row5.=str_repeat($x,$row1L- $row5L).'<td>'.$row['winner'].'</td>';
                                            $row5L=$row1L;

                                            //dito nangyari ung overflow
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

                                
?>
<div class="scrollit">
<table class="center bg-light">
				<tbody>
                    <?php echo $row1 ?>
                    <?php echo $row2 ?>
                    <?php echo $row3 ?>
                    <?php echo $row4 ?>
                    <?php echo $row5 ?>
				</tbody>
			</table>  
</div>
