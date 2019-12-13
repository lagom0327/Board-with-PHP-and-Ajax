<?php require_once('./conn.php'); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Message Board</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href='./style.css' />
  </head>
  <body>
    <nav>
      <ul>
        <li class='nav__list'><a href='./index.php'>Message board</a></li>
        <li class='nav__list'><a href='./login.php'>登入</a></li>
        <li class='nav__list'><a href='./register.php'>註冊</a></li>
      </ul>
    </nav>
  <p class="notation">本站為練習用網站，因教學用途刻意忽略資安的實作，註冊時請勿使用任何真實的帳號或密碼</p>
    <section class="container">
      <h1>Register</h1>
      <form class="form" method="POST" action="./handle_register.php">
      <?php
        if (isset($_GET['length'])) echo "<p>資料長度錯誤</p>";
        if (isset($_GET['username'])) echo "<p>帳號已經被註冊</p>";
        if (isset($_GET['password'])) echo "<p>兩次密碼不相同</p>";
      ?>
        <label class="form__input_username icon icon_in_input"><input name="username" placeholder='username within 16 chars' required></label>
        <label class="form__input_nickname icon icon_in_input"><input name="nickname"  placeholder='nickname within 64 chars' required></label>
        <label class="form__input_password icon icon_in_input"><input name="password" type="password"  placeholder="password" required></label>
        <label class="form__input_password icon icon_in_input"><input name="password2" type="password" placeholder="password" required></label>
        <input class="btn" type="submit" value="OK"/>
      </form>
    </section>
  </body>
</html>