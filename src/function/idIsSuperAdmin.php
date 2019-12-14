<?php
  function idIsSuperAdmin($id) {
    include(dirname(dirname(__FILE__)) . '/conn.php');
    $stmt = $conn->prepare("SELECT permission FROM lagom0327_users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
      $row = $result->fetch_assoc();
      return ($row['permission'] === 'super admin');
    } else die("fail:" . $conn->error);
  }
?>