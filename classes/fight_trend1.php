<?php 
require_once('../config.php');

?>
<style>
.center {
  margin-left: auto;
  margin-right: auto;
}
.btn-circle.btn-sm {
            width: 30px;
            height: 30px;
            padding: 6px 0px;
            border-radius: 15px;
            font-size: 8px;
            text-align: center;
        }
.scrollit {
    overflow-y:scroll;
    height:150px;
	
}
</style>


<div class="scrollit">
<table class="center">
				<tbody>
							<?php 
                $i = 0;
								$qry = $conn->query("SELECT * from draws order by id DESC");
								while($row = $qry->fetch_assoc()):                                                                            
							?>



							<td align="center">
							<?php if($row['winner'] == '1'): ?>
								<button type="button" class="btn btn-danger btn-circle btn-sm"><?php echo $row['drawno'] ?></button>
							<?php elseif($row['winner'] == '2'): ?>
								<button type="button" class="btn btn-primary btn-circle btn-sm"><?php echo $row['drawno'] ?></button>
							<?php elseif($row['winner'] == '3'): ?>
								<button type="button" class="btn btn-warning btn-circle btn-sm"><?php echo $row['drawno'] ?></button>
							<?php elseif($row['winner'] == '4'): ?>
								<button type="button" class="btn btn-dark btn-circle btn-sm"><?php echo $row['drawno'] ?></button>
							<?php else: ?>					
							<?php endif; ?>
							</td>
								<?php if($i % 10 == 0): ?>
								<tr></tr>
								<?php endif; ?>							
                            <?php $i++; ?>
							<?php endwhile; ?>
                                                       
				</tbody>
			</table>  
</div>
