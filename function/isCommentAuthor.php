<?php
require_once(dirname(dirname(__FILE__)) . '/conn.php');

function isCommentAuthor ($conn) {
    $id = isset($_POST['id']) ? $_POST['id'] : $_GET['id'];
    $stmt = $conn->prepare("SELECT user_id FROM lagom0327_comments WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
      $row = $result->fetch_assoc();
      return ((int)$row['user_id'] === $_SESSION['user_id']);
    } else die("fail:" . $conn->error);
}

?>