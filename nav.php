<?php
  require_once('./conn.php');
  require_once('./sessionStatus.php');
  require_once('./function/isAdmin.php');
  require_once('./function/isSuperAdmin.php');

  function printNav($sessionStatus) {
    function printLoginNav() {
      echo "<li class='nav__list'><a href='./handle_logout.php'>登出</a></li>";
      if (isAdmin()) {
        echo   "<li class='nav__list'><a href='./admin.php'>管理後臺</a></li>";
      } else if (isSuperAdmin()) {
        echo   "<li class='nav__list'><a href='./super_admin.php'>管理權限後臺</a></li>";
      }
    }
  
    function printLogoutNav() {
      echo "
          <li class='nav__list'><a href='./login.php'>登入</a></li>
          <li class='nav__list'><a href='./register.php'>註冊</a></li>";
    }
    if ($sessionStatus) {
      printLoginNav();
    } else {
      printLogoutNav();
    }
  }
?>

<nav>
  <ul>
    <li class='nav__list'><a href='./index.php'>Message board</a></li>
    <?php printNav($sessionStatus); ?>
  </ul>
</nav>


