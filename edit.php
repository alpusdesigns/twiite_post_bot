<!--
    edit.php  
    質問の新規作成・削除・更新等をするPHPファイル
-->
<?php 
    session_start();
?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TwitterQAお題投稿サービス</title>

    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/style.css">

    <!-- Remember to include jQuery :) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>

    <!-- jQuery Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.16/dist/vue.js"></script>

    <!-- Font awesome -->
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">

</head>
<body>
    
    <div id="header">
        <div class="inner">
            <h1 class="floatL">TwitterQAお題自動投稿サービス:編集画面
                </h1>
                
                <!-- ログイン情報を表示する -->
                <div id="login_state" class="floatR">

                <?php
                    if(!isset($_SESSION['access_token'])){
                        echo "<a href='pre_login.php'>ログイン</a>";
                    }else{
                        echo '<div style="display:flex; line-height: 50px; margin-top: 10px; width: 350px;">';
                        echo "<p><img src=" .$_SESSION['profile_image_url_https'] . "></p>";
                        echo "<p>" .$_SESSION['name'] . "さん</p>";
                        echo "<p><a href='logout.php'>ログアウト</a></p>";
                        echo "</div>";
                    }
                ?>

            </div>
            
            <div class="clear"></div>
        </div>
    </div>
    
    <div id="main">
        <div class="inner">
            <h2>TwitterQAお題投稿サービス</h2>
            
            <div id="qa_contents">
                <input type="button" v-on:click="openModalInsert()" value="&#xf304; 新規作成" class="contents_btn_insert fas">

                <input type="button" v-on:click="deleteAllItems(itemCheckbox)" value="削除" >
                
                <input type="button" v-on:click="csvDL" value="CSV出力" >

                <div class="qa_content inner" v-for="(item, index) in contents">
                    <div class="qa_contents_left">
                        <input type="checkbox" :value="item.id" v-model="itemCheckbox">

                        <p class="qa_contents_left_q">質問{{item.id}}:</p>
                        <p class="question_area">{{item.question}}</p>
                        <p class="answer_area">{{item.answer}}</p>
                    </div>
                    <div class="qa_contents_right">
                        <input type="button" v-on:click="questionPosts(item)" value="&#xf4ad;" class="contents_btn fas">

                        <!-- 編集画面を開く -->
                        <input type="button" v-on:click="openModal(item,index)" value="&#xf304;" class="contents_btn fas">

                        <input type="button" v-on:click="deliteItems(item)" value="&#xf1f8;" class="contents_btn fas">

                    </div>

                    <div class="clear"></div>
                </div>  
                
                <div class="link_container">

                    <a href="#modal_table_contents" rel="modal:open">質問の一覧を開く</a>

                    <div class="tl_content">
                        
                        <?php 
                            if(!isset($_SESSION['screen_name'])){
                                echo "<a class='twitter-timeline' data-width='600' data-height='500' href='デフォルトで設定したいアカウントのタイムラインURL'>Tweets by </a>"; 
                            
                            }else{
                                echo "<a class='twitter-timeline' data-width='600' data-height='500' href='https://twitter.com/" .$_SESSION['screen_name']. "?ref_src=twsrc%5Etfw'>Tweets by" .$_SESSION['screen_name']. "</a>";
                                
                            }
                        ?>

                    </div>

                </div>

                <!--テーブル編集項目を表示する-->
                <div class="edit_modal none">
                    <div class="edit_modal_content">
                        <p>質問{{editId}}</p>
                        <textarea class="question_area" placeholder="質問(100文字以内)を入力してください!" maxlength="100" v-model="editQuestion">{{editQuestion}}</textarea>

                        <textarea class="answer_area" placeholder="解答(100文字以内)を入力してください!" maxlength="100" v-model="editAnswer">{{editAnswer}}</textarea>
                        <input type="button" v-on:click="updateItems()" value="更新">

                        <input type="button" v-on:click="closeModal" value="閉じる">
                    </div>
                </div>

                <!--テーブル編集項目(挿入)を表示する-->
                <div class="edit_modal_insert none">
                    <div class="edit_modal_content">
                        <p>新規の質問を入力してください</p>
                        <textarea class="question_area" placeholder="質問(100文字以内)を入力してください!" maxlength="100" v-model="editQuestionInsert">{{editQuestionInsert}}</textarea>

                        <textarea class="answer_area" placeholder="解答(100文字以内)を入力してください!" maxlength="100" v-model="editAnswerInsert">{{editAnswerInsert}}</textarea>
                        <input type="button" v-on:click="insertItems()" value="挿入">

                        <input type="button" v-on:click="closeModalInsert" value="閉じる">
                    </div>
                </div>
           
            </div>
        </div>

        <!--テーブルの一覧表示をする-->
        <div id="modal_table_contents" class="none">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>質問</th>
                        <th>答え</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="item in contents">
                        <td class="alignC">{{item.id}}</td>
                        <td>{{item.question}}</td>
                        <td>{{item.answer}}</td>
                    <tr>
                </tbody>
            </table>

            <a href="#" rel="modal:close" class="link_btn">閉じる</a>
        </div>

    </div>
    
    <div id="footer">
        <div class="inner">
            <p>&copy; 社名 2020</p>
        </div>
    </div>

    <?PHP 
        //データベースの出力＋エラー確認
        require_once('const.php');

        //SQL文の実行
        //q_and_a_table内の内容をID順で取得する
        $query = "SELECT * FROM q_and_a_table ORDER BY id asc";
        $result = $mysqli->query($query);

        if(!$result){
            error_log($mysqli->connect_error);
            exit;
        }

        //JSON文字列として取得したあと配列に渡す
        $data_query = array();
        $data_query_json = '';
        $data_query_string = '';

        $result_lenght = $result->num_rows;
        $i = 0;


        // 結果の出力
        foreach ($result as $row) {
        
            $data_query[$i] = array('id'=>$row['id'],'question'=>$row['question'], 'answer'=>$row['answer'],'states'=>$row['states']);

            //JSON形式で取得する
            $data_query_json = json_encode($data_query[$i], JSON_UNESCAPED_UNICODE);

            //文字列をjson置換するときの処理
            if($i === $result_lenght -1){
                $data_query_string = $data_query_string . $data_query_json;
            }else{
                $data_query_string = $data_query_string . $data_query_json. ',';
            }

            $i++;

        }

        //JSON配列にして格納する
        $data_query_string = '[' . $data_query_string . ']';

    ?>

    <script>

        var dataContents =  <?php echo $data_query_string; ?>;

       //JSONオブジェクトチェック
       console.log(dataContents);

       var access_token = "<?php echo $_SESSION['oauth_token']; ?>";
       var access_token_secret = "<?php echo $_SESSION['oauth_token_secret']; ?>";

       //Vue.jsで展開する
       var vm = new Vue({
           el: '#qa_contents',
           data: {
               contents: dataContents,

                //編集時の値
                editId: '',
                editQuestion: '',
                editAnswer: '',
                editIndex: 0,

                //挿入時の値
                editQuestionInsert:'',
                editAnswerInsert:'',

                //チェックボックスの値
                itemCheckbox: [],
                deleteItemIndexs: [],

                //Postの時に必要なAccessTokenとAccessTokenSecretの値
                access_token: access_token,
                access_token_secret: access_token_secret,

           }, 
            methods:{
                //質問の投稿
                questionPosts: function(item){
                    if(confirm('選択した要素をつぶやきます、よろしいですか？')){
                        $.ajax({
                            type: "POST", 
                            url: "./postTwiite.php", 
                            data:{ 
                                question: item.question,
                                answer: item.answer,
                                access_token: this.access_token,
                                access_token_secret: this.access_token_secret,
                            }, 
                            dataType : "json", 
                            scriptCharset: 'utf-8' 
                        }).then(
                        function( data ){
                            console.log( item ); 
                        },
                        function( XMLHttpRequest, textStatus, errorThrown ){
                        });
                        
                    }else{
                        console.log(item.question);
                    }
                },

                //質問内容の消去
                deliteItems: function(item){
                    console.log(item.id);
                    if(confirm('選択した要素を削除します、よろしいですか？')){

                        $.ajax({
                        type: "POST", 
                        url: "./querysql.php", 
                        data:{ 
                            id: item.id,
                            question: item.question,
                            answer: item.answer,
                            state: item.state,
                            mode: "Delete"
                            }, 
                            dataType : "json", 
                            scriptCharset: 'utf-8' 
                        }).then(
                        function( data ){
                            console.log( item ); 
                        },
                        function( XMLHttpRequest, textStatus, errorThrown ){
                        });

                        var index = this.contents.indexOf(item);
                        this.contents.splice(index,1);

                    }
                },

                //質問内容の一斉消去
                deleteAllItems: function(itemCheckbox){
                    
                    //データのオブジェクトから配列変換
                    var itemCheckboxIndex = Array.from(itemCheckbox);
                    console.log(itemCheckboxIndex);

                    if(confirm('選択した要素を削除します、よろしいですか？')){

                        $.ajax({
                        type: "POST", 
                        url: "./querysql.php", 
                        data:{ 
                            ids: itemCheckboxIndex,
                            mode: "DeleteAny"
                            }, 
                            dataType : "json", 
                            scriptCharset: 'utf-8' 
                        }).then(
                        function( data ){
                            console.log( data ); 
                        },
                        function( XMLHttpRequest, textStatus, errorThrown ){
                        });

                        //DOMの再描画
                        location.reload();
                    }
                },

                openModalInsert: function(){
                    $(".edit_modal_insert").toggleClass('none');
                },
                
                closeModalInsert: function(){
                    $(".edit_modal_insert").toggleClass('none');
                },
                

                //質問内容の挿入
                insertItems: function(){
                    $index = this.contents.lenght;
                    $index = $index + 1;
                    console.log(this.editQuestionInsert);
                    console.log(this.editAnswerInsert);

                    // モーダル編集後
                    if(confirm('記述した要素を挿入します、よろしいですか？')){
                        $.ajax({
                            type: "POST", 
                        url: "./querysql.php", 
                        data:{ 
                            question: this.editQuestionInsert,
                            answer: this.editAnswerInsert,
                            state: '1',
                            mode: "Insert"
                        }, 
                        dataType : "json", 
                        scriptCharset: 'utf-8' 
                    }).then(
                        function( data ){
                            console.log( data ); 
                        },
                        function( XMLHttpRequest, textStatus, errorThrown ){
                        });
                        
                    this.contents.push = {
                        id: $index,
                        question: this.editQuestionInsert,
                        answer: this.editAnswerInsert,
                    };
                    
                        $(".edit_modal_insert").toggleClass('none');
                        //DOMの再描画
                        vm.$forceUpdate();

                        //DOMの再描画
                        //ページの再読み込み
                        location.reload();
                    }
                },
                
                //質問内容の編集ウインドウを開く
                openModal: function(item, index){
                    this.editId = this.contents[index]['id'];
                    this.editQuestion = this.contents[index]['question'];
                    this.editAnswer = this.contents[index]['answer'];
                    this.editIndex = index;

                    $(".edit_modal").toggleClass('none');

                },

                //質問内容の編集ウインドウを閉じる
                closeModal: function(){
                    $(".edit_modal").toggleClass('none');
                },
                                
                //質問内容の更新
                updateItems: function(){

                    console.log(this.editId);
                    console.log(this.editQuestion);
                    console.log(this.editAnswer);
                    console.log(this.editIndex);
                    
                    // モーダル編集後
                    if(confirm('記述した要素を更新します、よろしいですか？')){
                        $.ajax({
                            type: "POST", 
                     url: "./querysql.php", 
                     data:{ 
                         id: this.editId,
                         question: this.editQuestion,
                         answer: this.editAnswer,
                         state: '1',
                         mode: "Update"
                         }, 
                         dataType : "json", 
                         scriptCharset: 'utf-8' 
                     }).then(
                     function( data ){
                         console.log( data ); 
                     },
                     function( XMLHttpRequest, textStatus, errorThrown ){
                     });

                    this.contents[this.editIndex] = {
                        id: this.editId,
                        question: this.editQuestion,
                        answer: this.editAnswer,
                    };
                  
                     $(".edit_modal").toggleClass('none');
                     //DOMの再描画
                     vm.$forceUpdate();
                    }
                },

                //CSV出力
                csvDL: function(){
                    if(confirm('CSVデータの出力を行いますか？')){
                        var csvForm = '\ufeff' +'id,question,answer,states\n';
                        this.contents.forEach(el => {
                            var line = el['id'] + ',' + el['question'] + ',' + el['answer']+ ',' + el['states'] + '\n';
                            csvForm += line;
                        });

                        let blob = new Blob([csvForm], {type : 'text/csv'});
                        let link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = 'QAResult.csv';
                        link.click();
                    }
                }

            }
       });

       //Vue.jsで展開する
       var vm_table = new Vue({
           el: '#modal_table_contents',
           data: {
               contents: dataContents,
           }, 
       });


    </script>



    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>


</body>
</html>