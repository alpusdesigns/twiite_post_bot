<!--
    callback.php  
    callbackしたときに必要な情報を取得するPHPファイル
-->

<?php
    session_start();

    define("Consumer_Key", "登録したTwitterAPIのコンシューマキー(APIキー)を入れる");
    define("Consumer_Secret", "登録したTwitterAPIのコンシューマキー(シークレットキー)を入れる");

    require_once('./twitteroauth/autoload.php');
    use Abraham\TwitterOAuth\TwitterOAuth;
    
if($_SESSION['oauth_token'] == $_GET['oauth_token'] and $_GET['oauth_verifier']){
    
        //TwitterOAuth をインスタンス化
        $connection = new TwitterOAuth(Consumer_Key, Consumer_Secret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
        $access_token = $connection->oauth('oauth/access_token', array('oauth_verifier' => $_GET['oauth_verifier'], 'oauth_token'=> $_GET['oauth_token']));

        //取得したアクセストークンでユーザ情報を取得
        $user_connection = new TwitterOAuth(Consumer_Key, Consumer_Secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);
        $user_info = $user_connection->get('account/verify_credentials');


        //ユーザー情報の取得
        $id = $user_info->id;
        $name = $user_info->name;
        $screen_name = $user_info->screen_name;
        $profile_image_url_https = $user_info->profile_image_url_https;

        $_SESSION['access_token'] = $access_token;
        $_SESSION['id'] = $id;
        $_SESSION['name'] = $name;
        $_SESSION['screen_name'] = $screen_name;
        $_SESSION['profile_image_url_https'] = $profile_image_url_https;

        $_SESSION['oauth_token'] = $access_token['oauth_token'];
        $_SESSION['oauth_token_secret'] = $access_token['oauth_token_secret'];

        header('Location: login_home.php' );
        exit();
    }else{
        header('Location: twitter_pre_login.php' );
        exit();
    }
?>