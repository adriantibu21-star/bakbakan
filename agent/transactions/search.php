<?php
require_once('../../config.php');
if(isset($_POST['query'])){

 $output='';
 $query = "SELECT * from `template` where name like '%".$_POST['query']."%'";
 $result = mysqli_query($conn,$query);
 $output = '<ul style =" background-color:#eee;  cursor:pointer;"  class="list-unstyled">';
         if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result))
             {
             $output .= '<li style=" padding:12px;">'.$row["description"].'</li>';
             }
         }
         else{
           $output .= '<li>Not found on templates table</li>';
         }
 $output .= '</ul>';
 echo $output;
}
?>



