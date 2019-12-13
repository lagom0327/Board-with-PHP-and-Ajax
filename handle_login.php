<?php
  require_once('./conn.php');

  function setIPSession() {
    // https://blog.xuite.net/coke750101/networkprogramming/18668645-php+session%E5%8F%8Acookie+%E5%AE%89%E5%85%A8%E6%80%A7%E8%AD%B0%E9%A1%8C+++
    if(!isset($_SESSION)) session_start(); 
    if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]) )    {
      // echo "有經過其他代理主機：".$_SERVER["HTTP_X_FORWARDED_FOR"];
      $temp_ip = split(",", $_SERVER["HTTP_X_FORWARDED_FOR"]);
      $user_ip = $temp_ip[0];
    } else $user_ip = $_SERVER["REMOTE_ADDR"];
    $_SESSION['user_ip'] = $user_ip;
  }


  function setSession($data) { 
    if (!isset($_SESSION)) session_start();
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['nickname'] = $data['nickname'];
    $_SESSION['permission'] = $data['permission'];
    setIPSession();
  }
  
  function isCorrectUser($conn) {
    $stmt = $conn->prepare("SELECT * FROM lagom0327_users WHERE username=?");
    $stmt->bind_param("s", $_POST['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
      if ($result->num_rows ===  1) {
        $row = $result->fetch_assoc();
        $stmt->close();
        if (password_verify($_POST['password'], $row['password'])) return $row;
      }
      die(header('Location: ./login.php?username=' . $name));
    } else die('fail : '. $conn->error);
  }

  function checkData() {
    if (empty($_POST['username']) || empty($_POST['password'])) die('請檢查資料');
  }

  checkData();
  $row = isCorrectUser($conn);
  setSession($row);
  setcookie("user_id", $_SESSION['user_id'], time()+3600*24);
  if ($_SESSION['permission'] === 'admin') setcookie("permission", $_SESSION['permission'], time()+3600*24);
  if (include('./function/isAdmin.php')) header('Location: ./admin.php');
  else if (include('./function/isSuperAdmin.php')) header('Location: ./super_admin.php');
  else header('Location: ./index.php');

?>