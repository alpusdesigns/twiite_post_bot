<!--
    postTwiite.php  
    質問と解答をつぶやく処理をまとめたPHPファイル
-->
<?php
    session_start();

    //つぶやき関係の処理
    header('Content-type: application/json; charset=utf-8');
    
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    //Twitter処理
    require_once('./twitteroauth/autoload.php');
    use Abraham\TwitterOAuth\TwitterOAuth;

    //コンシューマキー(APIキー)
    $consumer_key = '登録したTwitterAPIのコンシューマキー(APIキー)を入れる';

    //コンシューマキー(シークレットキー)
    $consumer_secret = '登録したTwitterAPIのコンシューマキー(シークレットキー)を入れる';

    //アクセストークン
    $access_token = $_POST['access_token'];
    
    //アクセストークン(シークレットキー)
    $access_secret = $_POST['access_token_secret'];;
    
     //TwitterOAuthをインスタンスにする
    $TweetConnection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token,$access_secret);

    //質問、画像込みでつぶやく(固定画像)
    $media_id = $TweetConnection->upload('media/upload', ['media' => './img/create.jpg']);

    $param = ['status' => $question, 'media_ids' => $media_id->media_id_string];

    //質問が投稿されたら、解答をつぶやく
    $resultTweet = $TweetConnection->post("statuses/update", $param);

    // 1分後にもう一度つぶやく
    if($TweetConnection->getLastHttpCode() == 200) {
        sleep(60);
        $resultTweet = $TweetConnection->post(
            "statuses/update",
            array("status" => $answer)
        );    
     
    } 

?>