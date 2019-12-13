<?php
  require_once('./conn.php');
  require_once('./function/isAdmin.php');

  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  
  function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'utf-8');
  }

  function getAllData ($type, $conn, $page) {
    $offset = ($page - 1) * 20;
    if ($type === 'users') $sql = "SELECT * FROM lagom0327_users WHERE is_deleted='0' ORDER BY id LIMIT  ?, 20";
    else {
      $sql = "SELECT A.id, A.user_id, A.content, A.created_at, A.is_deleted, U.nickname FROM lagom0327_comments as A JOIN lagom0327_users as U ON A.user_id = U.id WHERE A.is_deleted=0 AND A.layer=0 ORDER BY A.created_at DESC LIMIT  ?, 20";
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $offset);
    if ($stmt->execute()) return $stmt->get_result();
    return null;
  }


    // 編輯和新增按紐 -----------------------------
    function printEditeSection ($type, $id) {
      echo "<div class='message__edite'>";
      echo   "<button class='edite_btn btn icon' title='edite' data-id={$id}></button>";
      echo   "<button title='delete' class='btn delete_btn icon' data-id={$id} data-type={$type}>
              </button>";
      echo "</div>";
    }

    function printAddComentBtn($row) {
      echo "<div class='message_edite_wrapper'>";
      echo "<div class='message__edite'>";
      echo   "<button class='add_btn btn icon' title='Add Comment' data-id={$row['id']}></button>";
      echo "</div>";
      echo "</div>";
    }

    // 列印換頁按紐用 ------------------------------
    function countPages ($type, $conn) {
      $tableName = "lagom0327_" . $type;
      $sql = "SELECT count(*) FROM $tableName WHERE is_deleted=0";
      if ($type === 'comments') $sql = $sql . " AND layer=0";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
      if ($result) {
        $row = $result->fetch_assoc();
        return ceil($row['count(*)'] / 20);
      } 
      return null;
    }

    function printPageBtn($type, $pageNow, $totalPage) {
      echo "<section class='page_btn__section'>";
        for ($i = 1; $i <= $totalPage; $i++) {
          if ($i === (int)$pageNow) echo "<button class='page_btn btn active' data-page='$i' >$i</button>";
          else echo "<a href='./$type.php?page=$i'><button class='page_btn btn' data-page='$i' >$i</button></a>";
        }
      echo "</section>";
    }
    // 列印使用者資料-----------
    function printUser($row) {
      echo "<tr class='user_data'>
              　<td class='user_table__td'>{$row['id']}</td>
              　<td class='user_table__td'>". escape($row['username']) . "</td>
              　<td class='user_table__td'>" . escape($row['nickname']) . "</td>
              　<td class='user_table__td permission__th'>{$row['permission']}</td>
                <td class='user_table__td'>";
      if ($row['permission'] !== 'super admin') printEditeSection('user',$row['id']);
          echo "</td>
            </tr>";
    }

    // 列印 留言----------------
    function printChildMessage($parentData, $conn) {
      $stmt = $conn->prepare("SELECT A.id, A.user_id, A.content, A.created_at, A.is_deleted, U.nickname FROM lagom0327_comments as A JOIN lagom0327_users as U ON A.user_id = U.id WHERE A.is_deleted=0 AND A.layer=1 AND A.parent_layer_id=? ORDER BY A.created_at ASC");
      $stmt->bind_param("i", $parentData['id']);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $authorClass = ($parentData['user_id'] === $row['user_id']) ? 'author' : '';
          echo "<div class='child_message message  $authorClass'>";
          echo  "<header>";
          echo    "<h4 class='message__nickname'>From: " . escape($row['nickname']) . "</h4>";
          echo    "<h4 class='message__time'>{$row['created_at']}</h4>";
          echo  "</header>";
          echo  "<p>" . escape($row['content']) . "</p>";
          if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === (int)$row['user_id'] || isAdmin()) {
            printEditeSection('comment', $row['id']);
          }
          echo "</div>";
        }
      }
    }

    function printMessage($row, $conn, $sessionStatus) {
      echo "<div class='message'>";
      echo  "<header>";
      echo    "<h3 class='message__nickname'>From: " . escape($row['nickname']) . "</h3>";
      echo    "<h4 class='message__time'>{$row['created_at']}</h4>";
      echo  "</header>";
      echo  "<p>" . escape($row['content']) . "</p>";
      if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === (int)$row['user_id'] || isAdmin()) {
        printEditeSection('comment', $row['id']);
      }
      printChildMessage($row, $conn);
      if ($sessionStatus) printAddComentBtn($row);
      echo "</div>";
    }
    // -----------------------

    function printAllData($type, $conn, $page, $sessionStatus) {
      $data = getAllData($type, $conn, $page);
      while ($row = $data->fetch_assoc()) {
        if ($type === 'users') printUser($row);
        else printMessage($row, $conn, $sessionStatus);
      }
    }
    



?>