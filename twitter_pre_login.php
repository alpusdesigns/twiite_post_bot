<!--
    Twitter_pre_login.php  
    Twitterのログイン前のPHPファイル
-->

<?php 
    session_start();
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
                header('Content-type: text/html; charset=utf-8');

                if(!isset($_SESSION['access_token'])){
                    echo "<a href='twitter_login.php' class='login_link_btn'>Twitterでログインする</a>";
                }else{
                    echo '<div id="pre_login_state">';
                    echo "<p><img src=" .$_SESSION['profile_image_url_https'] . "></p>";
                    echo "<p>" .$_SESSION['name'] . "さん</p>";
                    echo "<p><a href='twitter_logout.php'>ログアウト</a></p>";
                    echo "<p><a href='index.php'>ホームへ戻る</a></p>";
                    echo "</div>";
                }
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