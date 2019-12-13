<?php 
require_once('./conn.php'); 
require_once('./sessionStatus.php');
require_once('./function/isAdmin.php');
require_once('./utils.php');

if (!isAdmin($conn)) header('Location: ./index.php');

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
    <section class="container">
      <h1>Message board  管理後台</h1>
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