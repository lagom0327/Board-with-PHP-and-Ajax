<?php 
require_once('./conn.php'); 
require_once('./sessionStatus.php');
require_once('./utils.php');
require_once('./function/isSuperAdmin.php');

if (!isSuperAdmin()) header('Location: ./index.php');
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
      <h1>Message board  管理權限後台</h1>
        <table class='users_table'>
          <tr>
          　<th class='user_table__th'>Id</th>
          　<th class='user_table__th'>Username</th>
          　<th class='user_table__th'>Nickname</th>
          　<th class='user_table__th '>Permission</th>
            <th class='user_table__th'>Edite</th>
          </tr>
          <?php 
          
          printAllData('users', $conn, $page, $sessionStatus); ?>
        </table>
        <p>按下 Esc 可取消編輯</p>

        <?php printPageBtn('super_admin', $page, countPages('users', $conn)); ?> 
    </section>
    <script src='index.js'></script>
  </body>
</html>