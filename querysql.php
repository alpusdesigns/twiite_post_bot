<!--
    querysql.php  
    SQLの処理を記述したPHPファイル
-->

<?php
    //データベースの出力＋エラー確認
    require_once('const.php');

    $mode = $_POST['mode'];
    $id = $_POST['id'];
    $question_id = $_POST['question_id'];
    $states = $_POST['states'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];
    $user = $_POST['user'];
    

    $ids = $_POST['ids'];

    //データの削除を行う
    if(strcmp($mode,"Delete") == 0){

        $query = "DELETE FROM q_and_a_tables WHERE user = '${user}' and question_id = '${question_id}'";
        $result = $mysqli->query($query);
    
        if(!$result){
            error_log($mysqli->connect_error);
            exit;
        }    
    }

    // 選択したデータ削除を行う
    if(strcmp($mode,"DeleteAny") == 0){
        
        foreach($ids as $indexId){
            $query = "DELETE FROM q_and_a_tables WHERE user = '${user}' and question_id = '${indexId}'";
            $result = mysqli_query($mysqli, $query);
        }

        if(!$result){
            error_log($mysqli->connect_error);
            exit;
        }    
    }
  
    //データの更新を行う
    if(strcmp($mode,"Update") == 0){
        
        $query = "UPDATE q_and_a_tables SET question_id = '${question_id}', question = '${question}', answer = '${answer}' WHERE user = '${user}' and question_id = '${question_id}'";

        $result = $mysqli->query($query);

        if(!$result){
            error_log($mysqli->connect_error);
            exit;
        }   
    }
    
    //データの挿入を行う
    if(strcmp($mode,"Insert") == 0){
        
        //欠番を選択した後に挿入する
        $insertQuery = "INSERT INTO q_and_a_tables(user, question_id , question, answer)
        SELECT '${user}', MIN(insT.question_id) + 1, '${question}','${answer}' FROM q_and_a_tables insT LEFT OUTER JOIN q_and_a_tables insTB ON insT.question_id + 1 = insTB.question_id WHERE insTB.question_id IS NULL";

        $result = $mysqli->query($insertQuery);

        if(!$result){
            error_log($mysqli->connect_error);
            exit;
        }   
    }

?>