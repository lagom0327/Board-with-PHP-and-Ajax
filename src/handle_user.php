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

function editePermission($content, $id, $conn) {
  $stmt = $conn->prepare("UPDATE lagom0327_users SET permission=? WHERE id=?");
  $stmt->bind_param("si", $content, $id);
  if (!$stmt->execute()) exit(json_encode('fail'));
  $stmt->close();
  echo json_encode('編輯成功');
}

  function deleteUser($conn, $id) {
    $stmt = $conn->prepare("UPDATE lagom0327_users SET is_deleted=1 WHERE id=?");
    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) exit(json_encode('fail'));
    echo json_encode('刪除成功');
  }

  function notAuthor() {
    echo(json_encode('You aren\'t the author!'));
    header('HTTP/1.1 401  Unauthorized');
  }
  
  function noParameter($str) {
    echo(json_encode('lack of ' . $str));
    header('HTTP/1.1 400 bad request');
  }

  $method = $_SERVER['REQUEST_METHOD'];
  if (!$sessionStatus || !isSuperAdmin($conn)) exit(notAutho());

  
  switch ($method) {
    case 'GET':
      get20User($conn);
    break;
    
    case 'POST':
      if(empty($_POST['id'])) exit(noParameter('id'));
      if(empty($_POST['permissionOption'])) exit(noParameter('permission'));
      if ($_POST['permissionOption'] !== 'normal' && $_POST['permissionOption'] !== 'admin') exit(header('HTTP/1.1 400 bad request'));
      // exit(json_encode($_POST['permissionOption']));
      editePermission($_POST['permissionOption'], $_POST['id'], $conn);
    break;
    case 'DELETE':
      if (!isset($_GET['id']) || empty($_GET['id'])) exit(noParameter('id'));
      if (idIsSuperAdmin($_GET['id'])) exit(header('HTTP/1.1 400 bad request'));
      deleteUser($conn, $_GET['id']);
    break;
    default:
      header('HTTP/1.1 404 Not Found');
    break;
  }


  // if (!isSuperAdmin($conn) || !$_GET['id']) header('Location: ./super_admin.php');
  // if (idIsSuperAdmin($_GET['id'])) die('super admin cannot change');
  // deleteUser($conn);
  // header("Location: ./super_admin.php"); 

?>