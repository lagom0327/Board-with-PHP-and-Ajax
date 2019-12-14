<?php require_once('./conn.php'); ?>
<!DOCTYPE html>
<html>
  <?php include_once('templ_head.php');?>
  <body>
  <?php include_once('templ_nav.php'); ?>
    <section class="container">
      <h1 class="mb-3 mt-3">Register</h1>
      <form class="form form-register" method="POST" action="./handle_register.php">
      <?php
        if (isset($_GET['length'])) echo "<div class='alert alert-info' role='alert'>
        資料長度錯誤
      </div>";
        if (isset($_GET['username'])) echo "<div class='alert alert-info' role='alert'>
        帳號已經被註冊
      </div>";
        if (isset($_GET['password'])) echo "<div class='alert alert-info' role='alert'>
        兩次密碼不相同
      </div>";
      ?>
        <div class="form-group row">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text username icon "></span>
            </div>
            <input type="text" class="form-control" name="username" placeholder='username within 16 chars' required>
          </div>
        </div>
        <div class="form-group row">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text nickname icon "></span>
            </div>
            <input type="text" class="form-control" name="nickname" name="nickname"  placeholder='nickname within 64 chars'>
          </div>
        </div>
        <div class="form-group row">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text password icon "></span>
            </div>
            <input type="password" class="form-control" name="password" type="password"  placeholder="password" required>
          </div>
        </div>
        <div class="form-group row">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text password icon"></span>
            </div>
            <input type="password" class="form-control" name="password2" type="password"  placeholder="password" required>
          </div>
        </div>
        <button type="submit" class="btn btn-outline-info">OK</button>
      </form>
      <!-- <form class="form" method="POST" action="./handle_register.php">
        <input class="btn" type="submit" value="OK"/>
      </form> -->
    </section>
    <script src="./handleNavClass.js"></script>
  </body>
</html>