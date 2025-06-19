<?php

$host = 'localhost'; // 主機位置（本機）
$dbname = 'guestbook'; // 資料庫名稱
$user = 'root'; // 資料庫使用者名稱（XAMPP 預設為 root）
$pass = ''; // 密碼（XAMPP 預設為空字串）

// 使用 PDO（PHP Data Objects）連線 MySQL 資料庫
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}

// 取得表單提交的資料
$name = htmlspecialchars($_POST['name'] ?? '匿名');
$emoji = $_POST['emoji'] ?? 'happy.png';
$message = $_POST['message'] ?? '';

// 檢查留言是否為空白，並儲存進資料庫
if (trim($message) !== '') {
    $stmt = $pdo->prepare("INSERT INTO messages (name, emoji, message) VALUES (?, ?, ?)");
    $stmt->execute([$name, $emoji, htmlspecialchars($message)]);
}

// 返回首頁
header('Location: index.php');
exit;
