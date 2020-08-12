<!--
    regist_insert.php
    会員登録完了PHPファイル
-->
<?php 
session_start();

header("Content-type: text/html; charset=utf-8");

//クリックジャッキング対策
header("X-FRAME-OPTIONS; SAMEORIGIN");

//データベースの出力＋エラー確認
require_once('const.php');

$errors = array();

if(empty($_POST)) {
    header("Location: regist_mail_form.php");
    exit();
}else{
    
    $mail = $_SESSION['mail'];
    $account = $_SESSION['account'];

    $password_hash = password_hash($_SESSION['password'], PASSWORD_DEFAULT);
    
    //24時間以内かつ未登録者なら登録
    $query = "INSERT INTO member (account,mail,password) VALUES ('${account}','${mail}','${password_hash}' )";
    $result = $mysqli->query($query);

    $updatequery = "UPDATE pre_member SET flag=1 WHERE='${mail}'";
    $resultUpdate = $mysqli->query($updatequery);

    $_SESSION = array();

    if(isset($_COOKIE["PHPSESSID"])){
        setcookie("PHPSESSID", '', time() - 1800, '/');
    }

    session_destroy();

}


?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> 会員登録完了画面 </title>

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

            <?php if (count($errors) === 0):?>

                <p style="padding: 100px 0;">会員登録が完了いたしました！</p>
               
            <?php elseif (count($errors) > 0):?>

            <?php foreach($errors as $value){
                echo "<p>".$value."</p>";   
            }
            ?>

            <?php endif; ?>
            <a href="index.php">トップへ戻る</a>

        </div>
    </div>
    

    <div id="footer">
        <div class="inner">
            <p>&copy;  2020</p>
        </div>
    </div>

</body>
</html>