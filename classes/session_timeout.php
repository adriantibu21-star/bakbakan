<?php 
$minutesBeforeSessionExpire=1;
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > ($minutesBeforeSessionExpire*60))) {
  session_unset();     // remove all session variables   
  session_destroy();   // destroy session data  
  redirect('index.php');
}
$_SESSION['LAST_ACTIVITY'] = time();
?>