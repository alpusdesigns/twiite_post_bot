<!--
    const.php  
    MySQLの接続情報を設定をするPHPファイル
-->

<?php

    //MySQL 接続情報
    //データベースサーバー
    $hostName="データベースのサーバー名を入れる"; 

    //データべースのユーザー名
    $User="データベースのユーザー名を入れる";
    
    //データベース名
    $DBName="データベースの名称を入れる";      
    
    //パスワード
    $PassWord="データベースのパスワードを入れる";

    $mysqli = new mysqli($hostName, $User, $PassWord, $DBName);

    if ($mysqli->connect_error){ 
        echo $mysqli->connect_error;
        exit;
    }else{
        $mysqli->set_charset("utf8");
    }

    return $mysqli;

?>