<?php 
require_once('./conn.php'); 
require_once('./sessionStatus.php');
require_once('./utils.php');
if($sessionStatus && !isset($_COOKIE["user_id"])) die(header('Location: ./handle_logout.php'));
function printCommentBoard() {
  echo "<h2 class='notation text-secondary'>Hello ~ " . escape($_SESSION['nickname']) . "</h2>";
  echo "<section class='comment_board' >";
  echo "<form method='POST' action='./handle_add.php' >
          <div class='comment_board_input'><textarea class='w-100 comment_board_text' name='content' rows='6' placeholder='What do you want to say ?' required></textarea></div>";
  echo "  <button type='submit' class='btn comment_board_btn btn-outline-info'>Send</button>";
  echo "</form>";
  echo "</section>";
}
?>

<!DOCTYPE html>
<html>
<?php include_once('templ_head.php');?>
  <body>
  <?php include_once('templ_nav.php'); ?>
    <section class="container">
      <?php if ($sessionStatus) printCommentBoard(); ?>
      <div class="messages">
        <?php 
          printAllData('messages', $conn, $page, $sessionStatus);
          printPageBtn('index', $page, countPages('comments', $conn));
        ?> 
      </div>
    </section>
    <script defer src="./handleNavClass.js"></script>
    <script defer src='index.js'></script>
  </body>
</html>