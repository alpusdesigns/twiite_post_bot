<!--
    logout.php  
    TwitterのログアウトをするPHPファイル
-->

<?php 
    session_start();
    header('Content-type: text/html; charset=utf-8');
?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TwitterQAお題投稿サービス</title>
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <div id="header">
        <div class="inner">
            <h1 class="floatL">TwitterQAお題自動投稿サービス</h1>

            <!-- ログイン情報を表示する -->
            <div id="login_state" class="floatR"></div>
            <div class="clear"></div>
        </div>
    </div>

    <div id="main">
        <div class="inner">
            <?php

                $_SESSION = array();

                //セッションクッキーを破棄する
                if(isset($_COOKIE["PHPSESSID"])){
                    setcookie("PHPSESSID", '', time() -1800, '/');
                }

                //セッションを破棄する
                session_destroy();

                echo "<h2>ログアウトしました。</h2>";
                echo "<a href='pre_login.php'>ログインページへ</p>";
                echo "<p><a href='index.php'>ホームへ戻る</a></p>";

            ?>
        </div>
    </div>
    
    <div id="footer">
        <div class="inner">
            <p>&copy; 社名 2020</p>
        </div>
    </div>

</body>
</html>
