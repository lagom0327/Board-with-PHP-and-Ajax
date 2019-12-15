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
      echo   "<button class='add_btn btn icon' title='Add Comment' data-id={$row['id']}></button>";
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
      $prePage = $pageNow - 1;
      $nextPage = $pageNow + 1;
      echo  "
        <nav class='my-3' aria-label='pagination'>
          <ul class='justify-content-center pagination pagination'>
            ";
      if ((int)$pageNow === 1) echo "
      <li class='page-item disabled'>
        <a class='page-link' href='#' tabindex='-1' aria-disabled='true'><span aria-hidden='true'>&laquo;</span></a>
      </li>";
      else echo "
            <li class='page-item'>
              <a class='page-link' href='./$type.php?page=$prePage' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a>
            </li>";
        for ($i = 1; $i <= $totalPage; $i++) {
          if ($i === (int)$pageNow) echo "
          <li class='page-item active page_btn' aria-current='page' data-page='$i'>
            <span class='page-link'>
              $i
              <span class='sr-only'>(current)</span>
            </span>
          </li>
          ";
          else echo "
          <li class='page-item page_btn'><a class='page-link' href='./$type.php?page=$i'>$i</a></li>
          ";
        }
        if ((int)$pageNow === (int)$totalPage) echo "        
          <li class='page-item disabled'>
            <a class='page-link' href='#' tabindex='-1' aria-disabled='true'>&raquo;</a>
          </li>";
        else echo "        
        <li class='page-item'>
          <a class='page-link' href='./$type.php?page=$nextPage' aria-label='Next'><span aria-hidden='true'>&raquo;</span></a>
        </li>";
        echo "
            </ul>
          </nav>
        ";
    }
    // 列印使用者資料-----------
    function printUser($row) {
      echo "<tr class='user_data'>
              　<th scope='row'  align='center' class='user_table__td'>";
              echo $row['id'];
              echo "</th>
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
          echo "<div class='card bg-light child_message message  $authorClass'>";
          echo  "<div class='card-body'>";
          echo "<div class='message__header'>";
          echo    "<h5 class='card-title message__nickname'>" . escape($row['nickname']) . "</h5>";
          echo    "<h6 class='float-right text-muted message__time'>{$row['created_at']}</h6>";
          echo "</div>";
          echo  "<p class='card-text message__content'>" . escape($row['content']) . "</p>";
          if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === (int)$row['user_id'] || isAdmin()) {
            printEditeSection('comment', $row['id']);
          }
          echo "</div>";
          echo "</div>";
        }
      }
    }

    function printMessage($row, $conn, $sessionStatus) {
      echo "<div class='card bg-light message w-100'>";
      echo   "<div class='card-body'>";
      echo "<div class='message__header'>";
      echo    "<h4 class='card-title message__nickname'>" . escape($row['nickname']) . "</h4>";
      echo    "<h5 class='float-right text-muted message__time'>{$row['created_at']}</h5>";
      echo "</div>";
      echo  "<p class='card-text message__content'>" . escape($row['content']) . "</p>";
      if (isset($_SESSION['user_id']) && $_SESSION['user_id'] === (int)$row['user_id'] || isAdmin()) {
        printEditeSection('comment', $row['id']);
      }
      printChildMessage($row, $conn);
      if ($sessionStatus) printAddComentBtn($row);
      echo  "</div>";
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