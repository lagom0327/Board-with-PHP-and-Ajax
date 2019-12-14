<?php 
require_once('./conn.php'); 
require_once('./sessionStatus.php');
require_once('./function/isAdmin.php');
require_once('./utils.php');

if (!isAdmin($conn)) header('Location: ./index.php');

?>


<!DOCTYPE html>
<html>
<?php include_once('head.php');?>
  <body>
  <?php include_once('nav.php'); ?>
    <section class="container">
      <div class="messages">
        <?php 
          printAllData('messages', $conn, $page, $sessionStatus);
          printPageBtn('admin', $page, countPages('comments', $conn));
        ?> 
      </div>
    </section>
    <script src='index.js'></script>
    <script src="./handleNavClass.js"></script>
  </body>
</html>