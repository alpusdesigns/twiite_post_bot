<!--
    login.php  
    TwitterのログインをするPHPファイル
-->
<?php
session_start();

define("Consumer_Key", "登録したTwitterAPIのコンシューマキー(APIキー)を入れる");
define("Consumer_Secret", "登録したTwitterAPIのコンシューマキー(シークレットキー)を入れる");
define('Callback', '登録したTwitterAPIのコールバックURLを入れる');

require "twitteroauth/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

//TwitterOAuthインスタンス化
$connection = new TwitterOAuth(Consumer_Key, Consumer_Secret);
$request_token = $connection->oauth("oauth/request_token", array("oauth_callback" => Callback));

$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

$url = $connection->url("oauth/authorize", array("oauth_token" => $request_token['oauth_token']));
header('Location: ' . $url);
?>