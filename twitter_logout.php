<!--
    twitter_logout.php  
    TwitterのログアウトPHPファイル
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
        
                //Twitterセッションを破棄する
                unset($_SESSION['access_token'] );
                unset($_SESSION['id'] );
                unset($_SESSION['name'] );
                unset($_SESSION['screen_name'] );
                unset($_SESSION['profile_image_url_https'] );
                unset($_SESSION['oauth_token'] );
                unset($_SESSION['oauth_token_secret'] );   

                echo "<h2>Twitterのログアウトをしました。</h2>";
                echo "<a href='twitter_pre_login.php'>ログインページへ</p>";
                echo "<p><a href='index.php'>ホームへ戻る</a></p>";

            ?>
        </div>
    </div>
    
    <div id="footer">
        <div class="inner">
            <p>&copy;  2020</p>
        </div>
    </div>

</body>
</html>








