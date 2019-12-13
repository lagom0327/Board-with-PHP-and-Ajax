<?php
  function deleteSession($sessionId) {
    if ($sessionId) {
      session_unset();
      session_destroy();
    }
  } 

  if (!isset($_SESSION)) session_start();
  setcookie("user_id", NULL);
  setcookie("permission", NULL);
  deleteSession(session_id());
  header('Location: ./index.php');

?>