<!--
    regist_mail_insert.php
    会員仮登録完了PHPファイル
-->

<?php 
session_start();
?>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> メール確認画面</title>

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
        
        <p style="padding-top: 50px;">
        <?php
        header("Content-type: text/html; charset=utf-8");

        //CSFR対策のトークン判定を行う
        if($_POST['token'] != $_SESSION['token']){
            echo "不正アクセスの可能性";
            exit();
        }

        //クリックジャッキング対策
        header("X-FRAME-OPTIONS; SAMEORIGIN");

        //データベースの出力＋エラー確認
        require_once('const.php');

        $errors = array();

        //メール判定
        if(empty($_POST)) {
            header("Location: mail_form.php");
            exit();
        }else{
            $mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;

            //メール入力判定
            if($mail == ''){
                $errors['mail'] = "メールアドレスが入力されていません";
            }else{
                if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)){
                    $errors['mail_check'] = "メールアドレスの形式が正しくありません.";
                }
            }
        }

        if(count($errors) === 0){

            $urltoken = hash('sha256', uniqid(rand(),1));
            $url ="http://PHPフォルダの格納場所/regist_form.php" . "?urltoken=" .$urltoken;
            
            //仮登録用のTableに接続して登録する
            $query = "INSERT INTO pre_member (urltoken,mail,date) VALUES ('${urltoken}','${mail}',now() )";
            $result = $mysqli->query($query);

            //メールの宛先
            $mailto = $mail;

            $returnMail = 'albion.inst@gmail.com';

            $name = '';
            $email = '';
            $subject = "会員登録の確認メール";

            $body = <<<EOM
            24時間以内に下記のURLからご登録の程、よろしくお願いいたします。
            {$url}
            EOM;

            //メール送信
            mb_language("Japanese");
            mb_internal_encoding("UTF-8");

            $header = 'From: ' .mb_encode_mimeheader($name). '<' .$mail. '>';

            if(mb_send_mail($mailto, $subject, $body, $header, '-f'.$returnMail)){

                $_SESSION = array();

                if(isset($_COOKIE["PHPSESSID"])){
                    setcookie("PHPSESSID", '', time() - 1800, '/');
                }

                session_destroy();

                echo "メール送信をいたしました24時間以内にメールに記載されたURLからご登録ください！";
            }else{
                echo "メール送信失敗です";
            }
        }
            
        ?>
        </p>
          <?php if (count($errors) === 0):?>

            <div id="main_form_style">
            <p><?=$message?></p> 
            <p>以下のURLが記載されたメールが届きます</p> <br/>  
            <a href="<?=$url?>"><?=$url?></a>;   

            <?php elseif (count($errors) > 0):?>

            <?php foreach($errors as $value){
                echo "<p>".$value."</p>";   
            }
            ?>

            <p><input type="button" value="戻る" onClick="histry.back()"></p>

            <?php endif; ?>
            </div>
        </div>
    </div>
    

    <div id="footer">
        <div class="inner">
            <p>&copy;  2020</p>
        </div>
    </div>

</body>
</html>