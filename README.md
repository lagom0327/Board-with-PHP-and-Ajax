# 留言板
## [Demo](http://sio2.tw/board/)
## Indruction 
使用 PHP 和 Ajax 建造留言板和 MySQL 建立會員系統

## Screenshot 
### 留言板畫面
![image](https://user-images.githubusercontent.com/49493665/70850562-b4914a80-1ec6-11ea-857d-b78c0bf0c530.png)
### 管理權限後台畫面
![image](https://user-images.githubusercontent.com/49493665/70850441-5ca61400-1ec5-11ea-81f9-3935cb8724d6.png)


- 編輯、刪除和新增都是使用 Ajax 不用換頁，集中到 `handle_messag.php` 和 `handle_user.php` API 處理，
- 用 Admin 帳號登入時，在 `index.php` 或 `admin.php` 能處理所有人的留言
- 使用 Super Admin 帳號登入時，可修改其他會員的權限或刪除會員

- 會員系統
  - 使用 MySQL 存儲會員資料，密碼用 Hash（單向雜湊）加密和 使用 `password_verify` 函式確認密碼
  ```php
    function addUser($name, $pass, $nickname, $conn) {
    $hash = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 11]);
    $stmt = $conn->prepare("INSERT INTO lagom0327_users(username, password, nickname) VALUES(?, ?, ?)");
    $stmt->bind_param("sss", $name, $hash, $nickname);
    $stmt->execute();
    $stmt->close();
  }
  ```
  - 使用 PHP Session 建立通行機制
<!-- ### 未完成
- 按鈕沒有處理重複觸發 -->



<!--prepared statement 中 table name 不能使用佔位符 -->
<!-- Server response 格式不是指定的也會變 error -->