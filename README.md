## hw3：加強留言板

之前在課程中講過 Bootstrap 這一個好用的 library，能夠讓你把版面變得好看一點，現在就請你利用 Bootstrap 改造之前的留言板 UI。

另外，請把發送留言跟刪除留言的地方改成 ajax，新增留言跟刪除的時候都不用換頁，藉此增進使用者體驗。

最後，我們在之前有實作過「通行證」的機制，其實在 PHP 裡面有內建的可以用，而這個機制就叫做 session。可以參考 [PHP 5 Sessions](https://www.w3schools.com/php/php_sessions.asp) 或是 [PHP Session 使用介紹，啟用與清除 session](http://www.webtech.tw/info.php?tid=33)，把之前留言板的作業改成用 PHP 內建的 session 機制，而不是用我們自己實作的。

- 編輯、刪除和新增都是使用 Ajax ，集中到 `handle_messag.php` 處理

- 因為 Cookie 只有一天時效但 Session 不是，當兩個不同步。沒有 `user_id` 這個 Cookie 但 PHPSESSID 有對應的存在 Server 端時會進行一次登出。

- 為了維持一個頁面固定 20 個留言，做任何有關留言的操作都會刷新當前頁面的 20 個留言。這樣不能用 `$.hide(200)` 的動畫了 QQ

- 用 Admin 帳號登入時，在 `index.php` 就能處理所有人的留言


### 未完成
- 按鈕沒有處理重複觸發
- 沒有使用 Bootstrap 
- super_admin.php 沒有使用 Ajax

<!--prepared statement 中 table name 不能使用佔位符 -->
<!-- Server response 格式不是指定的也會變 error -->