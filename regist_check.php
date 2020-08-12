<!--
    regist_check.php
    会員登録確認用PHPファイル
-->

<?php 
session_start();

header("Content-type: text/html; charset=utf-8");

//CSFR対策のトークン判定を行う
if($_POST['token'] != $_SESSION['token']){
    echo "不正アクセスの可能性";
    exit();
}

//クリックジャッキング対策
header("X-FRAME-OPTIONS; SAMEORIGIN");

//空白のスペース削除
function spaceTriming($str){
    $str = preg_replace('/^[ 　]+/u', '', $str);
    $str = preg_replace('/[ 　]+$/u', '', $str);
    return $str;
}

$errors = array();

if(empty($_POST)) {
    header("Location: regist_mail_form.php");
    exit();
}
else{
    
    $account = isset($_POST['account_name']) ? $_POST['account_name'] : NULL;
    $password = isset($_POST['password']) ? $_POST['password'] : NULL;
  
    $account = spaceTriming($account);
    $password = spaceTriming($password);
  
    $password_hide = str_repeat('*', strlen($password));

}

if(count($errors) === 0){
    $_SESSION['account'] = $account;
    $_SESSION['password'] = $password;
}

?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> 会員登録確認画面 </title>

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

            <form action="regist_insert.php" method="post">
                <p>メールアドレス:<?=htmlspecialchars($_SESSION['mail'], ENT_QUOTES)?></p><br/>

                <p>アカウント名：<?=htmlspecialchars($_SESSION['account'] , ENT_QUOTES)?></p><br/>

                <p>PassWord:<?=$password_hide?></p>

                <input type="hidden" name="taken" value="<?=$_POST['token']?>">
                <input type="submit" value="登録する">

            </form>
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