<!--
    index.php  
    サービス開始の初期画面のPHPファイル
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
            <div id="login_state" class="floatR">
            <?php
                if(!isset($_SESSION['access_token'])){
                    echo "<a href='pre_login.php'>ログイン</a>";
                }else{
                    echo '<div style="display:flex; line-height: 50px; margin-top: 10px; width: 350px;">';
                    echo "<p><img src=" .$_SESSION['profile_image_url_https'] . "></p>"; 
                    echo "<p>" .$_SESSION['name'] . "さん</p>";
                    echo "<p><a href='logout.php'>ログアウト</a></p>";
                    echo "</div>";
                }
            ?>

            </div>

            <div class="clear"></div>
        </div>
    </div>

    <div id="main">
        <div class="inner">
            <h2>TwitterQAお題投稿サービス</h2>

            <div class="main_desc">
                <p>このサービスではTwitterのお題投稿サービスを<br />
                    簡単に設定できるサービスです！
                </p>
            </div>
            
            <a href="edit.php" class="link_btn"> 早速試してみる</a>
        </div>
    </div>
    

    <div id="footer">
        <div class="inner">
            <p>&copy; 社名 2020</p>
        </div>
    </div>

</body>
</html>