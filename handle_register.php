<?php
  require_once('./conn.php');
  $username = $_POST['username'];
  $nickname =  $_POST['nickname'];
  $password = $_POST['password'];
  $password2 = $_POST['password2'];

  function validData($username, $nickname,$password, $password2) {
    $maxLength = 16;
    if (empty($username) || empty($nickname) || empty($password) || empty($password2)) {
      echo ('請檢查資料');
      return false;
    } else if ($password !== $password2) {
      header('Location: ./register.php?password=1');
      return false;
    } else if (strlen($username) > $maxLength || strlen($password) > $maxLength ||  strlen($nickname) > $maxLength * 4) {
      header('Location: ./register.php?length=1');
      return false;
    }
    return true;
  }

  function sameUsername($name, $conn) {
    $stmt = $conn->prepare("SELECT username FROM lagom0327_users WHERE username=?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result= $stmt->get_result();
    if ($result->num_rows > 0) return true;
    else echo 'fail'. $conn->error;
  }

  function addUser($name, $pass, $nickname, $conn) {
    $hash = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 11]);
    $stmt = $conn->prepare("INSERT INTO lagom0327_users(username, password, nickname) VALUES(?, ?, ?)");
    $stmt->bind_param("sss", $name, $hash, $nickname);
    $stmt->execute();
    $stmt->close();
  }

  if (!validData($username, $nickname, $password, $password2)) die();
  if (sameUsername($username, $conn)) die(header('Location: ./register.php?username=' . $username));
  addUser($username, $password, $nickname, $conn);
  header('Location: ./login.php');
?>