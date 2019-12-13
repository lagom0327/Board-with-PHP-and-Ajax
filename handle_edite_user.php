<?php
require_once('./conn.php');
require_once('./sessionStatus.php');
require_once('./function/isSuperAdmin.php');
require_once('./function/idIsSuperAdmin.php');

function editePermission($content, $conn) {
  $stmt = $conn->prepare("UPDATE lagom0327_users SET permission=? WHERE id=?");
  $stmt->bind_param("si", $content,$_GET['id']);
  $stmt->execute();
  $stmt->close();
}

if (!$_GET['id'] || !$sessionStatus || !isSuperAdmin($conn)) {
  die(header("Location: ./index.php"));
}

$id = $_GET['id'];
$selectOption = $_POST['permissionOption'];
if ($selectOption !== 'normal' && $selectOption !== 'admin') die();
if (idIsSuperAdmin($id)) die("super admin can not change.");
editePermission($selectOption, $conn);
header("Location: ./super_admin.php"); 

?>