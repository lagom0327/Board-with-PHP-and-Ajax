<?php
  require_once('./conn.php'); 
  require_once('./sessionStatus.php');
  require_once('./function/isSuperAdmin.php');
  require_once('./function/idIsSuperAdmin.php');
  header('Content-Type: application/json; charset=UTF-8'); 

function get20User($conn) {
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'utf-8');
  }


  $offset = ($page - 1) * 20;
  $sql = "SELECT id, username, nickname, permission, created_at FROM lagom0327_users WHERE is_deleted=0 ORDER BY id ASC LIMIT  ?, 20";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $offset);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows <= 0) exit(json_encode('no data'));
  $arr= array();
  while ($row = $result->fetch_assoc()) {
    $row['nickname'] = escape($row['nickname']);
    $row['username'] = escape($row['username']);
    $row['permission'] = escape($row['permission']);
    array_push($arr, $row);
  }
  echo json_encode($arr);
  $conn->close();
}

  function deleteUser($conn, $id) {
    $stmt = $conn->prepare("UPDATE lagom0327_users SET is_deleted=1 WHERE id=?");
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) exit(json_encode('fail'));
    echo json_encode('刪除成功');
  }

  $method = $_SERVER['REQUEST_METHOD'];
  if (!$sessionStatus) exit('nosession');

  if (!isSuperAdmin($conn)) exit('not super admin');
  switch ($method) {
    case 'GET':
      // exit('GET');
      get20User($conn);
      break;
    case 'DELETE':
      if (!isset($_GET['id']) || empty($_GET['id'])) exit('id');
      if (idIsSuperAdmin($_GET['id'])) exit('super admin cannot change');
      deleteUser($conn, $_GET['id']);
  }


  // if (!isSuperAdmin($conn) || !$_GET['id']) header('Location: ./super_admin.php');
  // if (idIsSuperAdmin($_GET['id'])) die('super admin cannot change');
  // deleteUser($conn);
  // header("Location: ./super_admin.php"); 

?>