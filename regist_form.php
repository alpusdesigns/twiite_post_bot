<!--
    regist_form.php
    会員登録用PHPファイル
-->
<?php 
session_start();

header("Content-type: text/html; charset=utf-8");

//CSRF対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];

//クリックジャッキング対策
header("X-FRAME-OPTIONS; SAMEORIGIN");

//データベースの出力＋エラー確認
require_once('const.php');

$errors = array();

//メール判定
if(empty($_GET)) {
    header("Location: regist_mail_check.php");
    exit();
}
else{
    $urltoken = isset($_GET['urltoken']) ? $_GET['urltoken'] : NULL;
    //メール入力判定
    if($urltoken == ''){
        $errors['urltoken'] = "もう一度登録をやり直してください";
    }else{
        
        //24時間以内かつ未登録者なら登録
        $query = "SELECT mail FROM pre_member WHERE urltoken=('${urltoken}') AND flag =0 AND date > now() - interval 24 hour";
        $result = $mysqli->query($query);

        $row_count = $result->num_rows;

        if($row_count == 1){
            $mail_array = $result->fetch_array(MYSQLI_ASSOC);;
            $mail = $mail_array['mail'];
            $_SESSION['mail'] = $mail;
        }else{
            $errors['urltoken_timeover'] = "このURLはご利用できません。有効期限が切れたなどの問題があります。";
        }
    }
}

?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> 会員登録フォーム画面 </title>

    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/style.css">


    <!-- Remember to include jQuery :) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>

    <!-- jQuery  -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>


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

            <form action="regist_check.php" method="post" >
                <label for="mail_address">メールアドレス：</label>
                <input type="text" name="mail_address" pattern="^[a-zA-Z0-9!$&*.=^`|~#%'+\/?_{}-]+@([a-zA-Z0-9_-]+\.)+[a-zA-Z]{2,4}$" required><br/>

                <label for="account_name">アカウント名：</label>
                <input type="text" name="account_name" pattern="^[0-9A-Za-z]+$" required><br/>

                <label for="password">PassWord</label>
                <input type="text" name="password" pattern="^([a-zA-Z0-9]{4,})$" required><br/>

                <input type="hidden" name="token" value="<?=$token?>">

                <input type="submit" value="確認する">

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