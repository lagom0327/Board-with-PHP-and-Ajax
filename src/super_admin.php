<?php 
require_once('./conn.php'); 
require_once('./sessionStatus.php');
require_once('./utils.php');
require_once('./function/isSuperAdmin.php');

if (!isSuperAdmin()) header('Location: ./index.php');
?>


<!DOCTYPE html>
<html>
<?php include_once('templ_head.php');?>
  <body>
  <?php include_once('templ_nav.php'); ?>
    <section class="container">
    <div id="notation" class=''></div>
      <table class='users_table table-striped table-hover table-info'>
        <caption>List of users</caption>
          <thead>
            <tr>
              <th scope="col" class='user_table__th'>Id</th>
            　<th scope="col" class='user_table__th'>Username</th>
            　<th scope="col" class='user_table__th'>Nickname</th>
            　<th scope="col" class='user_table__th '>Permission</th>
            <th scope="col" class='user_table__th'>Edite</th>
          </tr>
            </thead>
            <tbody>
              <?php 
          
          printAllData('users', $conn, $page, $sessionStatus); ?>
          </tbody>
        </table>
        <div class="cancel_notation">按下 Esc 可取消編輯</div>
        
        <?php printPageBtn('super_admin', $page, countPages('users', $conn)); ?> 
    </section>
    <script defer src="./handleNavClass.js"></script>
    <script defer src='index.js'></script>
  </body>
</html>