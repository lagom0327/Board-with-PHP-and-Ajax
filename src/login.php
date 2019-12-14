<?php 
  require_once('./conn.php'); 
  require_once('./sessionStatus.php');
  if ($sessionStatus) {
    die(header('Location: ./index.php'));
  }

?>
<!DOCTYPE html>
<html>
<?php include_once('templ_head.php');?>
  <body>
  <?php include_once('templ_nav.php'); ?>

    <section class="container">
      <h1 class="mb-3 mt-3">Login</h1>
      
      <form class="form form-login" method="POST" action="./handle_login.php">
      <?php    
        if (isset($_GET['username'])) echo "<div class='alert alert-info' role='alert'>
        帳號或密碼輸入錯誤
      </div>";
      ?>
      <div class="form-group row">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text username icon "></span>
            </div>
            <input type="text" class="form-control" name="username" placeholder='username' required>
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
        <!-- <input name="password" type="password" required> -->
        <button type="submit" class="btn btn-outline-info">OK</button>
      </form>
    </section>
    <script src="./handleNavClass.js"></script>
  </body>
</html>