<?php 
require_once('./conn.php'); 
require_once('./sessionStatus.php');
require_once('./function/isAdmin.php');
require_once('./utils.php');

if (!isAdmin($conn)) header('Location: ./index.php');
?>
<!DOCTYPE html>
<html>
<?php include_once('./template/templ_head.php');?>
  <body>
  <?php include_once('template/templ_nav.php'); ?>
    <section class="container">
      <div class="messages">
        <?php 
          printAllData('messages', $conn, $page, $sessionStatus);
          printPageBtn('admin', $page, countPages('comments', $conn));
        ?> 
      </div>
    </section>
    <script defer src="./handleNavClass.js"></script>
    <script defer src='index.js'></script>
  </body>
</html>