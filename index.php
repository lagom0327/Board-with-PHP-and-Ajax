<?php 
require_once('./conn.php'); 
require_once('./sessionStatus.php');
require_once('./utils.php');
if($sessionStatus && !isset($_COOKIE["user_id"])) die(header('Location: ./handle_logout.php'));
function printCommentBoard() {
  echo "<section class='comment_board' >";
  echo "<h1 class='notation'>Hello ~ " . escape($_SESSION['nickname']) . "</h1>";
  echo "<form method='POST' action='./handle_add.php' >
          <div class='comment_board_input'><textarea class='comment_board_text' name='content' rows='10' placeholder='What do you want to say ?' required></textarea></div>";
  echo "  <input type='submit' class='btn comment_board_btn' value='Send' />";
  echo "</form>";
  echo "</section>";
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Message Board</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <link rel="stylesheet" href='./style.css' />
  </head>
  <body>
  <?php include_once('nav.php'); ?>
  <p class='notation'>本站為練習用網站，因教學用途刻意忽略資安的實作，註冊時請勿使用任何真實的帳號或密碼</p>
    <section class="container">
      <h1>Message board</h1>   
      <?php if ($sessionStatus) printCommentBoard(); ?>
      <div class="messages">
        <?php 
          printAllData('messages', $conn, $page, $sessionStatus);
          printPageBtn('index', $page, countPages('comments', $conn));
        ?> 
      </div>
    </section>
    <script src='index.js'></script>
  </body>
</html>