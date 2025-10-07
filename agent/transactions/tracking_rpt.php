<?php
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `transactions` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
$department_qry = $conn->query("SELECT id,name FROM department_list");
$dept_arr = array_column($department_qry->fetch_all(MYSQLI_ASSOC),'name','id');
?>
<style>
table, th, td {
  border: 2px solid black;
  border-collapse: collapse;
  font-size: 120%;
}
</style>

<div id="print_out">

<h3>DOCUMENT TRACKING SLIP</h3>
<h5 class="col-md-auto border-bottom px-2 border-dark w-100">DENR-Oriental Mindoro</h5> </br>
<h3 class="col-md-auto border-bottom px-2 border-dark w-100"><?php echo $trackingno; ?></h3>
<h4>Description:   <?php echo $description; ?></h4>
<h4>Date Created:  <?php echo date('m-d-Y:H:i',strtotime($date_created)); ?></h4>
<h4>Originating Office:  <?php echo isset($source) ? $dept_arr[$source] : 'N/A' ?></h4>
 
 <table class="table table-hover table-stripped">
         <thead>
         <tr>
         <th>Date Received:</th>
         <th>Date Released:</th>
         <th>Action Taken:</th>
	</tr>
	</thead>

	  <tr>
          <td></td>
          <td ></td>
          <td style="padding: 280px;"></td>
          </tr>
</table>
</div>




 <button class="btn btn-flat btn-success" type="button" id="print"><i class="fa fa-print"></i> Print</button>


<script>
	$(document).ready(function(){
        $('#print').click(function(){
            start_loader()
            var _h = $('head').clone()
            var _p = $('#print_out').clone();
            var _el = $('<div>')
            _el.append(_h)
            _el.append('<style>html, body, .wrapper {min-height: unset !important;}</style>')
            _el.append('<style>table, th, td {border: 2px solid black; border-collapse: collapse;font-size: 120%;}</style>')
            _el.append(_p)
            var nw = window.open("","_blank","width=1200,height=1200")
                nw.document.write(_el.html())
                nw.document.close()
                setTimeout(() => {
                    nw.print()
                    setTimeout(() => {
                        nw.close()
                        end_loader()
                    }, 300);
                }, 500);
        })
	})
	
</script>