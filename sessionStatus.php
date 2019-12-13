<?php
function validIp() {
  if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]) )  {
    $temp_ip = split(",", $_SERVER["HTTP_X_FORWARDED_FOR"]);
    $user2_ip = $temp_ip[0];
  } else {
    $user2_ip = $_SERVER["REMOTE_ADDR"];
  }

  if (isset($_SESSION["user_ip"]))
  //  echo "<BR />原來 session 的IP:".$_SESSION["user_ip"];
  // echo "<h1 style='display:none;'>目前使用者IP : $user2_ip</h1> ";

  if (isset($_SESSION["user_ip"]) && $_SESSION["user_ip"] !== $user2_ip ) {
    include('./handle_logout.php');
    return false;
  }  else return true;
}
if(!isset($_SESSION)) session_start();
if (isset($_SESSION['user_id']) && validIp()) $sessionStatus = true;
else $sessionStatus = false;
return $sessionStatus;
?>