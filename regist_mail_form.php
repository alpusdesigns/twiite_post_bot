<!--
    regist_mail_iorm.php
    会員仮登録用メール送信PHPファイル
-->

<?php 
session_start();

header("Content-type: text/html; charset=utf-8");

//CSRF対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];

//クリックジャッキング対策
header("X-FRAME-OPTIONS: SAMEORIGIN");

?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>

    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
    <div id="header">
        <div class="inner">
            <h1 class="floatL">TwitterQAお題自動投稿サービス</h1>

            <div class="clear"></div>
        </div>
    </div>

    <div id="main">
        <div class="inner">
            <form action="./regist_mail_check.php" method="post">
                <p>メールアドレス<input type="text" name="mail" size="50"></p>
                <input type="hidden" name="token" value="<?=$token?>">
                <p><input type="submit" name="send" value="登録する"></p>
            </form>
        </div>
    </div>
    

    <div id="footer">
        <div class="inner">
            <p>&copy;  2020</p>
        </div>
    </div>

</body>
</html>