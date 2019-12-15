# 留言板
## [Demo](http://sio2.tw/board/)
## Indruction 
使用 PHP 、 MySQL 和 Ajax 建造留言板和建立會員系統部屬至 AWS EC2
## Screenshot 
### 留言板畫面
![image](https://user-images.githubusercontent.com/49493665/70850562-b4914a80-1ec6-11ea-857d-b78c0bf0c530.png)
### 管理權限後台畫面
![image](https://user-images.githubusercontent.com/49493665/70850441-5ca61400-1ec5-11ea-81f9-3935cb8724d6.png)

## File Structure
```
.
├── src
│   ├── API
│   │   ├── handle_message.js
│   │   └── handle_user.js
│   ├── fuction
│   │   ├── idIsSuperAdmin.php
│   │   ├── isAdmin.php
│   │   ├── isCommentAuthor.php
│   │   └── isSuperAdmin.php
│   ├── admin.php
│   ├── conn.php
│   ├── handle_login.php
│   ├── handle_logout.php
│   ├── handle_register.php
│   ├── handleNavClass.js
│   ├── index.php
│   ├── login.php
│   ├── register.php
│   ├── sessionStatus.php
│   ├── style.css
│   ├── super_admin.php
│   └── utils.php
├── .eslintrc.js
├── .gitignore
├── package.json
├── package-lock.json
└── README.md
```

- 編輯、刪除和新增都是使用 Ajax 不用換頁，集中到 `handle_messag.php` 和 `handle_user.php` API 處理
  - 可以增加一層子留言
  - 內容為空不能送出 request
  - 為留言作者時可編輯或刪除留言
  - 同一時間只能編輯或新增一個留言
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
  - 會員身分有三種 `normal`, `admin` 和 `super admin`
  - 使用 PHP Session 建立通行機制
    - 通行證有效時間 24 hr
    - 使用中換 IP 會使通行證無效，需重新登入

## Built With
- [jQuery](https://jquery.com/) 
- [Bootstrap](https://getbootstrap.com/) / [Bootswatch](https://bootswatch.com/)
- [ESLint](https://eslint.org/) : Lint Code
- [AWS EC2](https://aws.amazon.com/tw/ec2/) / [Apache](https://httpd.apache.org/) : Deploy
