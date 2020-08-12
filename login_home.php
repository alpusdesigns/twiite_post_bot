<!--
    login_home.php  
    ユーザーページの設定画面PHPファイル
-->

<?php 
session_start();

header("Content-type: text/html; charset=utf-8");

//クリックジャッキング対策
header("X-FRAME-OPTIONS: SAMEORIGIN");

//データベースの出力＋エラー確認
require_once('const.php');

$query = "SELECT * FROM  member WHERE id = ".$_SESSION['user']." ";
$result = $mysqli->query($query);

if(!$result){
    print('クエリ取得が失敗しました' .$mysqli->error);
    $mysqli->close();
    exit();
}

while($row = $result->fetch_assoc()) {
    $account = $row['account'];
    $email = $row['mail'];
    $random_flag = $row['random_flag'];
}

//セッションに設定する
$_SESSION['account'] = $account;
$_SESSION['login_state'] = true;

$result->close();

// Twitterアカウントの情報を登録する
$oauth_token = $_SESSION['oauth_token'];
$oauth_token_secret = $_SESSION['oauth_token_secret'];

$twitter_acc_query = "SELECT * FROM  member WHERE acc_token_secret = '${oauth_token_secret}'";
$result_tacc = $mysqli->query($twitter_acc_query);

if(!$result_tacc){
    $caution_text = "Twitter登録をする必要があります。";

}else{   
    $update_acc_query = "UPDATE member SET acc_token = '${oauth_token}', acc_token_secret = '${oauth_token_secret}' WHERE account = '${account}'";

    $result_update_tacc = $mysqli->query($update_acc_query);
}

//選択情報を更新する
    //メンバーごとのアクセストークンを取得する
    $member_query = "SELECT * FROM member GROUP BY account";
    $member_result = $mysqli->query($member_query);
    
    $accounts = array();
    $acc_tokens = array();
    $acc_secrets = array();

    $i = 0;
    while($row = $member_result->fetch_assoc()) {
        $pre_member = $row['account'];
        $accounts[$i] = $pre_member;
    
        $pre_acc_token = $row['acc_token'];
        $acc_tokens[$i] = $pre_acc_token;
    
        $pre_acc_token_secret = $row['acc_token_secret'];
        $acc_secrets[$i] = $pre_acc_token_secret;

        $i++;
    }

    //ランダムツイートの更新
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $rand_num = $_POST['twitte_random'];

        $update_rand_query = "UPDATE member SET random_flag = '${rand_num}' WHERE account = '${account}'";

        $result_update_rand = $mysqli->query($update_rand_query);
        header( 'Location: ' . $_SERVER[ 'PHP_SELF' ] );
    }

?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員ページ</title>

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
            <h2>会員メニュー</h2>
                <p>名前：<?php echo $account;?></p>
                <br />
                <p>メールアドレス：<?php echo $email;?></p>
                <br />

                <!-- 定時ツイートをする -->
                <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" id="main_state_forms" style="padding:0px; line-height:1; text-align: center;">

                    <!-- ランダムツイートをする -->
                    <label name="twitte_random">ランダムツイートをする</label>
                    <select name="twitte_random" id="twitte_random">
                        <option <?php if($random_flag == 0){ echo "selected";}?> value = 0>する</option>
                        <option <?php if($random_flag == 1){ echo "selected";}?> value = 1>しない</option>
                    </select>
                    <br /><br />

                    <input type="submit" id="update_user_state" class="link_btn" style="    border-radius: 10px;" value="更新">                 
                </form>
 
                <?php if(!isset($_SESSION['name'])){?>
                    <p> <?=$caution_text?> </p>
                    <a href="twitter_login.php">ツイッターアカウントを登録する</a><br /><br />
                <?php }else{?>
                    <p>Twitter:<?=$_SESSION['name']; ?></p><br />
                    <a href="twitter_logout.php">ツイッターアカウントを解除する</a><br /><br />
                <?php } ?>

                <a href="index.php">トップへ戻る</a><br /><br />
                <a href="logout.php">ログアウト</a>
        </div>
    </div>
    
    <div id="footer">
        <div class="inner">
            <p>&copy;  2020</p>
        </div>
    </div>

</body>
</html>