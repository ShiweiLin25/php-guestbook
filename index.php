<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <title>訪客留言版</title>
    <style>
        .emoji-option {
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 8px;
        }

        /* 當某個 radio 被選取時，它後面緊接著的圖片（class 為 .emoji-option）會加上藍色邊框 */
        input[type="radio"]:checked+.emoji-option {
            border-color: #0d6efd;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container">
        <h2 class="text-center mb-4">訪客留言版</h2>

        <!-- 留言表單 -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <!-- 表單使用 POST 傳送到 save.php，將留言資料寫入資料庫 -->
                <form action="save.php" method="post">
                    <div class="mb-3">
                        <label class="form-label">暱稱</label>
                        <input type="text" name="name" class="form-control" required />
                    </div>
                    <!-- 表情選擇區，使用隱藏的 radio 與圖片搭配實現可點選圖片效果 -->
                    <div class="mb-3">
                        <label class="form-label">選擇你的代表表情</label>
                        <div class="d-flex gap-3">
                            <!-- 每一個 <label> 代表一個表情圖案，搭配隱藏的 radio，使用者點圖可選 -->
                            <label>
                                <input type="radio" name="emoji" value="smile.png" class="d-none" checked>
                                <img src="emoji/smile.png" class="img-thumbnail emoji-option" width="50">
                            </label>
                            <label>
                                <input type="radio" name="emoji" value="cry.png" class="d-none">
                                <img src="emoji/cry.png" class="img-thumbnail emoji-option" width="50">
                            </label>
                            <label>
                                <input type="radio" name="emoji" value="angry.png" class="d-none">
                                <img src="emoji/angry.png" class="img-thumbnail emoji-option" width="50">
                            </label>
                            <label>
                                <input type="radio" name="emoji" value="wow.png" class="d-none">
                                <img src="emoji/wow.png" class="img-thumbnail emoji-option" width="50">
                            </label>
                            <label>
                                <input type="radio" name="emoji" value="laugh.png" class="d-none">
                                <img src="emoji/laugh.png" class="img-thumbnail emoji-option" width="50">
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">留言</label>
                        <textarea name="message" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-outline-primary">送出留言</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 留言列表 -->
        <h4 class="mb-3">所有留言：</h4>
        <?php

        // 設定 MySQL 資料庫連線資訊（localhost 預設帳密）
        $host = 'localhost';
        $dbname = 'guestbook';
        $user = 'root';
        $pass = '';

        // 建立 PDO 資料庫連線，若錯誤則顯示錯誤訊息並中止程式
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        } catch (PDOException $e) {
            die("資料庫連線失敗：" . $e->getMessage());
        }

        // 取出所有留言資料，依照留言時間倒序排列
        $stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 如果有留言，則用迴圈逐筆印出內容
        if (count($messages) > 0) {
            foreach ($messages as $row) {
                // 使用 htmlspecialchars() 避免 XSS 攻擊，nl2br() 保留換行格式
                $name = htmlspecialchars($row['name']);
                $emoji = htmlspecialchars($row['emoji']);
                $message = nl2br(htmlspecialchars($row['message']));
                $time = $row['created_at'];

                // 組出一個留言卡片，包含頭像圖片、暱稱、留言與時間
                echo '
                <div class="card mb-3 shadow-sm">
                    <div class="card-body d-flex align-items-start">
                        <div class="me-3" style="width: 40px; height: 40px;">
                            <img src="emoji/' . $emoji . '" alt="emoji" width="40" height="40" />
                        </div>
                        <div>
                            <h5 class="card-title mb-1">' . $name . '</h5>
                            <p class="card-text mb-1">' . $message . '</p>
                        </div>
                    </div>
                    <p class="card-subtitle text-muted text-end mb-1 me-1" style="font-size: 0.9rem;">' . $time . '</p>
                </div>';
            }
        } else {
            // 如果沒有留言，就顯示提示訊息
            echo '<p class="text-muted">還沒有留言喔！</p>';
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>