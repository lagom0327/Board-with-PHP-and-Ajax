<?php
require_once(dirname(dirname(__FILE__)) . '/conn.php');
require_once(dirname(dirname(__FILE__)) . '/sessionStatus.php');
require_once(dirname(dirname(__FILE__)) . '/function/isCommentAuthor.php');
require_once(dirname(dirname(__FILE__)) . '/function/isAdmin.php');

header('Content-Type: application/json; charset=UTF-8'); 

function get20Mess($conn) {
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'utf-8');
  }
  function getSubCommits($conn, $id, $arr, $i) {
    $stmt = $conn->prepare("SELECT A.id, A.user_id, A.content, A.created_at, U.nickname FROM lagom0327_comments as A JOIN lagom0327_users as U ON A.user_id = U.id WHERE A.is_deleted=0 AND A.layer=1 AND A.parent_layer_id=? ORDER BY A.created_at ASC");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows <= 0) return $arr;
    $j = 0;
    while ($row = $result->fetch_assoc()) {
      $row['nickname'] = escape($row['nickname']);
      $row['content'] = escape($row['content']);
      $arr[$i]['sub'][$j] = $row;
      $j++;
    }
    return $arr;
  }

  function getMainMess ($conn, $page) {
    $offset = ($page - 1) * 20;
    $sql = "SELECT A.id, A.user_id, A.content, A.created_at, U.nickname FROM lagom0327_comments as A JOIN lagom0327_users as U ON A.user_id = U.id WHERE A.is_deleted=0 AND A.layer=0 ORDER BY A.created_at DESC LIMIT  ?, 20";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $offset);
    if ($stmt->execute()) return $stmt->get_result();
    return null;
  }

  $arr = array();
  $i = 0;
  $mainComments = getMainMess($conn, $page);
  while ($row = $mainComments->fetch_assoc()) {
    $row['nickname'] = escape($row['nickname']);
    $row['content'] = escape($row['content']);
    array_push($arr, array('main' => $row));
    $arr = getSubCommits($conn, $row['id'], $arr, $i);
    $i += 1 ;
  }
  echo json_encode($arr);
  $conn->close();
}

function addComment($conn) {
  $stmt = $conn->prepare("INSERT INTO lagom0327_comments(user_id, content) VALUES (?, ?)");
  $stmt->bind_param("is", $_SESSION['user_id'], $_POST['content']);
  if ($stmt->execute()) {
    echo json_encode(array('id' => $conn->insert_id));
  }
  $stmt->close();
  return null;
}

function addSubComment($conn) {
  $stmt = $conn->prepare("INSERT INTO lagom0327_comments(user_id, content, layer, parent_layer_id) VALUES (?, ?, 1, ?)");
  $stmt->bind_param("isi", $_SESSION['user_id'], $_POST['content'], $_POST['parentId']);
  if ($stmt->execute()) {
    echo json_encode(array('id' => $conn->insert_id));
  }
  $stmt->close();
}

function editeComment($conn) {
  $stmt = $conn->prepare("UPDATE lagom0327_comments SET content=? WHERE id=?");
  $stmt->bind_param("si", $_POST['content'], $_POST['id']);
  if (!$stmt->execute()) exit(json_encode('fail'));
  echo json_encode('success');
}

function deleteComment($conn) {
  $stmt = $conn->prepare("UPDATE lagom0327_comments SET is_deleted=1 WHERE id=?");
  $stmt->bind_param("i", $_GET['id']);
  if (!$stmt->execute()) exit(json_encode('fail'));
  echo json_encode('success');
}

function notAuthor() {
  echo(json_encode('You aren\'t the author!'));
  header('HTTP/1.1 403 forbidden');
}

function noParameter($str) {
  echo(json_encode('lack of ' . $str));
  header('HTTP/1.1 400 bad request');
}

$method = $_SERVER['REQUEST_METHOD'];

if (!$sessionStatus) {
  echo(json_encode('Your session has been terminated'));
  header('HTTP/1.1 401 Unauthorized');
  exit();
}

switch ($method) {
  case 'GET':
    get20Mess($conn);
  break;
  case 'POST':
    if (empty($_POST['content'])) exit(noParameter('content'));
    if (isset($_POST['parentId'])) addSubComment($conn);
    else if (isset($_POST['id'])) {
      if (empty($_POST['id'])) exit(noParameter('id'));
      if (!isCommentAuthor($conn) && !isAdmin($conn)) exit(notAuthor());
      editeComment($conn);
    }
    else addComment($conn);
  break;
  case 'DELETE':
    if (!isset($_GET['id']) || empty($_GET['id'])) exit(noParameter('id'));
    if (!isCommentAuthor($conn) && !isAdmin($conn)) exit(notAuthor());
    deleteComment($conn);
  break;
  default:
    header('HTTP/1.1 404 Not Found');
  break;
}
?>