<?php
  require_once('./conn.php');
  require_once('./sessionStatus.php');
  require_once('./function/isAdmin.php');
  require_once('./function/isSuperAdmin.php');

  function printNav($sessionStatus) {
    function printLoginNav() {
      if (isAdmin()) {
        echo "
        <li class='nav-item'>
          <a href='./admin.php' class='nav-link menu-item nav-active'>管理後臺</a>
        </li>";
      } else if (isSuperAdmin()) {
        echo "
        <li class='nav-item'>
          <a href='./super_admin.php' class='nav-link menu-item nav-active'>管理權限後臺</a>
        </li>
        ";
      }
      echo "
        <li class='nav-item'>
          <a href='./handle_logout.php' class='nav-link menu-item nav-active'>Log out</a>
        </li>
      ";
    }
  
    function printLogoutNav() {
      echo "
      <li class='nav-item'>
        <a href='./register.php' class='nav-link menu-item nav-active'>Register</a>
      </li>
      <li class='nav-item'>
        <a href='./login.php' class='nav-link menu-item nav-active'>Login</a>
      </li>
      ";
    }
    if ($sessionStatus) {
      printLoginNav();
    } else {
      printLogoutNav();
    }
  }
?>
<nav id="navbar" class="navbar navbar-expand-md fixed-top navbar-dark bg-info">
  <div class="d-flex flex-grow-1">
    <span class="w-100 d-lg-none d-block"><!-- hidden spacer to center brand on mobile --></span>
    <a id="logo" class="navbar-brand d-none d-lg-inline-block" href="#">
      Nav
    </a>
    <a class="navbar-brand-two mx-auto d-lg-none d-inline-block" href="#">
       <img src="//placehold.it/40?text=LOGO" alt="Nav">
    </a>
    <div class="w-100 text-right">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#myNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    </div>
  </div>
  <div class="collapse navbar-collapse flex-grow-1 text-right" id="myNavbar">
        <ul class="navbar-nav ml-auto flex-nowrap">
            <li class="nav-item">
                <a href="./index.php" class="nav-link menu-item nav-active">Home</a>
            </li>
            <?php printNav($sessionStatus); ?>
        </ul>
    </div>
  </div>
</nav>



