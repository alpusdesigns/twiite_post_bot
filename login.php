<!--
    login.php  
    TwitterのログインをするPHPファイル
-->

<?php 
session_start();

header("Content-type: text/html; charset=utf-8");

//クリックジャッキング対策
header("X-FRAME-OPTIONS: SAMEORIGIN");

//データベースの出力＋エラー確認
require_once('const.php');

?>

<?php
    //ログイン判定をする
    if(isset($_POST['login'])){

        $email = $mysqli->real_escape_string($_POST['mail']);
        $password = $mysqli->real_escape_string($_POST['password']);

        $query = "SELECT * FROM  member WHERE mail = '${email}'";
        $result = $mysqli->query($query);

        if(!$result){
            print('クエリ取得が失敗しました' .$mysqli->error);
            $mysqli->close();
            exit();
        }

        while($row = $result->fetch_assoc()) {
            $db_hash_password = $row['password'];
            $user_id = $row['id'];
        }

        $result->close();

        if(password_verify($password, $db_hash_password)){
            $_SESSION['user'] = $user_id;
            header('Location:login_home.php');
            exit;
        }else{
        ?>
            <div>メールアドレスが一致していません</div>
        <?php
        }
    }
?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>

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
            <h2>ログインフォーム</h2>
            <form method="post">
                <p>メールアドレス：<input type="text" name="mail" size="50"></p>
                <p>パスワード：<input type="text" name="password" size="50"></p>
                <p><input type="submit" name="login" value="ログイン"></p>
            </form>
        </div>
    </div>
    

    <div id="footer">
        <div class="inner">
            <p>&copy; 2020</p>
        </div>
    </div>

</body>
</html>