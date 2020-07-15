<!--
    querysql.php  
    SQLの処理をするためのPHPファイル
-->
<?php
    //データベースの出力＋エラー確認
    require_once('const.php');

    $mode = $_POST['mode'];
    $id = $_POST['id'];
    $states = $_POST['states'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    $ids = $_POST['ids'];

    //データの削除を行う
    if(strcmp($mode,"Delete") == 0){
        $query = "DELETE FROM `q_and_a_table` WHERE `q_and_a_table`.`id` = ${id}";
        $result = $mysqli->query($query);
    
        if(!$result){
            error_log($mysqli->connect_error);
            exit;
        }    
    }

    // 選択したデータ削除を行う
    if(strcmp($mode,"DeleteAny") == 0){
        
        foreach($ids as $indexId){
            $query = "DELETE FROM q_and_a_table WHERE id = '${indexId}'";
            // $result = $mysqli->query($query);
            $result = mysqli_query($mysqli, $query);
        }

        if(!$result){
            error_log($mysqli->connect_error);
            exit;
        }    
    }
  
    //データの更新を行う
    if(strcmp($mode,"Update") == 0){
        
        $query = "UPDATE q_and_a_table SET id = '${id}', question = '${question}', answer = '${answer}', states = '${states}' WHERE id = '${id}'";

        $result = $mysqli->query($query);

        if(!$result){
            error_log($mysqli->connect_error);
            exit;
        }   
    }
    
    //データの挿入を行う
    if(strcmp($mode,"Insert") == 0){
        
        $insertQuery = "INSERT INTO q_and_a_table(question, answer, states) VALUES ('${question}','${answer}','${states}')";

        $result = $mysqli->query($insertQuery);

        if(!$result){
            error_log($mysqli->connect_error);
            exit;
        }   
    }

?>