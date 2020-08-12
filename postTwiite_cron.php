<!--
    postTwiite_cron.php  
    質問と解答を(定時的につぶやく処理)をまとめたPHPファイル
-->

<?php
    session_start();

    //つぶやき関係の処理
    header('Content-type: application/json; charset=utf-8');
    
    //データベースの出力＋エラー確認
    require_once('const.php');

    //Twitter処理
    require_once('./twitteroauth/autoload.php');
    use Abraham\TwitterOAuth\TwitterOAuth;
    
    //コンシューマキー(APIキー)
    $consumer_key = '登録したTwitterAPIのコンシューマキー(APIキー)を入れる';

    //コンシューマキー(シークレットキー)
    $consumer_secret = '登録したTwitterAPIのコンシューマキー(シークレットキー)を入れる';
    
    //メンバーごとのアクセストークンを取得する
    $member_query = "SELECT * FROM member GROUP BY account";
    $member_result = $mysqli->query($member_query);
    
    $accounts = array();
    $acc_tokens = array();
    $acc_secrets = array();
    $random_flags = array();

    $i = 0;
    while($row = $member_result->fetch_assoc()) {
        $pre_member = $row['account'];
        $accounts[$i] = $pre_member;
    
        $pre_acc_token = $row['acc_token'];
        $acc_tokens[$i] = $pre_acc_token;
    
        $pre_acc_token_secret = $row['acc_token_secret'];
        $acc_secrets[$i] = $pre_acc_token_secret;

        $pre_random_flag = $row['random_flag'];
        $random_flags[$i] = $pre_random_flag;
    
        $i++;
    }
    
    
    //配列条件にあったをみる
    $quest_query = "SELECT * FROM q_and_a_tables GROUP BY question_id ASC";

    $quest_query_rand = "SELECT *, ( SELECT t2.question_id FROM q_and_a_tables t2 WHERE t2.user = t1.user ORDER BY rand() LIMIT 1) AS qid FROM q_and_a_tables t1 GROUP BY t1.user DESC";
    
    $q_and_a_result = $mysqli->query($quest_query);
    $q_and_a_result_rand = $mysqli->query($quest_query_rand);

    $contents = array();
    $questions = array();
    $answers = array();
    $randoms = array();

    $i = 0;
    $j = 0;
    
    //ランダムの値を取得する
    while($rows = $q_and_a_result_rand->fetch_assoc()) {

        $randoms[$j] = $rows['qid'];
        
        $j++;
    }
        
    $j = 0;
    $ss = array();
    while($rows = $q_and_a_result->fetch_assoc()) {
        $contents[$i]['question_id'] = $rows['question_id'];
        $contents[$i]['question'] = $rows['question'];
        $contents[$i]['answer'] = $rows['answer'];
        $contents[$i]['user'] = $rows['user'];
    
        $i++;
    }

    //ランダムツイート文の設定をする
    for($k = 0; $k < count($contents); $k++){
        if($randoms[$j] === $contents[$k]['question_id']){
            $questions[$j] =  $contents[$k]['question']; 
            $answers[$j] =  $contents[$k]['answer']; 
            $j++;
        }
    }

    $arraylenght = count($randoms);

    $i = 0;
    while($i < $arraylenght){
        $TweetConnection = new TwitterOAuth($consumer_key, $consumer_secret, $acc_tokens[$i],$acc_secrets[$i]);

        //質問、画像込みでつぶやく(固定画像)
        $media_id = $TweetConnection->upload('media/upload', ['media' => './img/create.jpg']);

        //ランダムツイートをする場合、定時でツイートする
        if($random_flags[$i] == 0){
            
            $param = ['status' => $questions[$i], 'media_ids' => $media_id->media_id_string];
    
            //質問が投稿されたら、解答をつぶやく
            $resultTweet = $TweetConnection->post("statuses/update", $param);
    
            // 5分後にもう一度つぶやく
            if($TweetConnection->getLastHttpCode() == 200) {
    
                sleep(350);
                        
                $resultTweetAnswer = $TweetConnection->post(
                    "statuses/update",
                    array("status" => $answers[$i])
                );
            }
        }

        $i++;
    }
?>