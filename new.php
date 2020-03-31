<?php
    try{
        require_once(__DIR__ .'/mybbs_db.php');
    }catch (Exception $e) {
        echo 'エラーが発生しました。:' . $e->getMessage();
    }
    $pdo = null;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>新規作成</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script type="text/javascript"> 
      function check(){
        var flag = 0;
        // 入力check
        if(document.new.name.value == ""){ // nameの入力をチェック
          flag = 1;
        }else if(document.new.title.value == ""){ // titleの入力をチェック
          flag = 1;
        }else if(document.new.content.value == ""){ // contentの入力をチェック
          flag = 1;
        }else{ // 入力があった場合文字数を数える
            nv = document.new.name.value;
            tv = document.new.title.value;
            cv = document.new.content.value;
            nl = nv.length;
            tl = tv.length;
            cl = cv.length;
        }
        // 設定終了
        if(flag){
          window.alert('入力しなさい'); // 入力漏れがあれば警告ダイアログを表示
          return false; // 送信を中止
        }else if(nl > 15){ // 入力文字数が多いと警告ダイアログを表示
            window.alert('名前長すぎ');
            return false; // 送信を中止 
        }else if(tl > 20){
            window.alert('タイトルなげえ');
            return false; // 送信を中止       
        }else if(cl > 100){
            window.alert('書き込みすぎ');
            return false; // 送信を中止
        }else{
          return true; // 送信を実行
        }  
      }
    </script>
</head>
<body>
<?php require_once('html/header.html'); ?>
<div class="contact-form">
    <div class="text-center">
        <div class="jumbotron">
            <h1 class="display-4">WatanabeBBS</h1><br>
            <h3>新規投稿ページ</h3><br>
            <h2>書きたいことをすきなだけ</h2><rb>
            <a class="btn btn-primary btn-lg" href="index.php" role="button">投稿一覧へ</a>
        </div>
    </div>
</div>
<div class="container">
    <form method="post" name="new" action="add.php" onSubmit="return check()">
    <div class="form-group row justify-content-center">
        <div class="col-4">    
            <lavel>名前</lavel><br>                 
                <input type="text" name="name"><br>
            <lavel>タイトル</lavel><br>                 
                <input type="text" name="title"><br>
            <lavel>投稿内容</lavel><br>
                <textarea name="content" rows="10" cols="60" wrap="soft"></textarea><br>
                <input class="btn btn-outline-secondary" type="submit" name="add" value="投稿する">
                <input class="btn btn-outline-warning" type="reset" value="リセット">
        </div>
    </div>    
    </form> 
</div>      
<?php require_once('html/footer.html'); ?>  
</body>
</html>