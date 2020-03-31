<?php
    //DBに接続
    try {
        require_once(__DIR__ .'/mybbs_db.php');
        function h($s) {
            return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
          } 
        //board_tableからidで表示内容を検索
        $stmt = $pdo->prepare('SELECT * FROM board_table WHERE id = :id');
        $stmt->execute(array(':id' => $_GET["id"]));
        $result = 0;
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    } catch (Exception $e) {
        echo 'エラーが発生しました。:' . $e->getMessage();
    }
    
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>編集画面</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script type="text/javascript"> 
    function check(){
        var flag = 0;
        // 入力check
        if(document.edit.name.value == ""){ // nameの入力をチェック
          flag = 1;
        }else if(document.edit.title.value == ""){ // titleの入力をチェック
          flag = 1;
        }else if(document.edit.content.value == ""){ // contentの入力をチェック
          flag = 1;
        }else{
            nv = document.edit.name.value;
            tv = document.edit.title.value;
            cv = document.edit.content.value;
            nl = nv.length;
            tl = tv.length;
            cl = cv.length;
        }
        // 設定終了
        if(flag){
          window.alert('入力しなさい'); // 入力漏れがあれば警告ダイアログを表示
          return false; // 送信を中止
        }else if(nl > 15){
            window.alert('名前長すぎ');
            return false; // 送信を中止 
        }else if(tl > 20){
            window.alert('タイトルなげえ');
            return false; // 送信を中止           
        }else if(cl > 100){
            window.alert('書き込みすぎ');
            return false; // 送信を中止
        }else{
            if(window.confirm('本当に大丈夫ですか？？')){ // 確認ダイアログを表示
                return true; // 「OK」時は送信を実行
            }else{ // 「キャンセル」時の処理
                window.alert('キャンセルされました'); // 警告ダイアログを表示
                return false; // 送信を中止
            }
        }
    }
    </script>
  </head>
<body>
    <?php require_once('html/header.html'); ?>
    <div class="contact-form">
    <div class="text-center">
        <div class="jumbotron">
            <h1 class="display-4">WatanabeBBS</h1>
            <h3><?php if (!empty($result['name'])) echo h($result['name'])?>が投稿した</h3>
            <h3><?php if (!empty($result['title'])) echo h($result['title'])?><br>の編集ページ</h3>
        </div>
    </div>
    </div>
    <div class="container">
        <div class="contact-form">
            <form method="post" name="edit" onSubmit="return check()">
                <div class="form-group row justify-content-center">
                    <div class="col-4">
                    <h1>編集する</h1> 
                        <input type="hidden" name="id" value="<?php if (!empty($result['id'])) echo h($result['id'])?>">
                    <p>
                        <label>名前</label><br>
                        <input type="text" name="name" value="<?php if (!empty($result['name'])) echo h($result['name'])?>">
                    </p>
                    <p>
                        <label>タイトル</label><br>
                        <input type="text" name="title" value="<?php if (!empty($result['title'])) echo h($result['title'])?>">
                    </p>
                    <p>
                        <label>投稿内容</label><br>
                        <textarea name="content" rows="10" cols="60" wrap="soft"><?php if (!empty($result['content'])) echo h($result['content'])?></textarea>
                    </p>
                        <input class="btn btn-outline-success" formaction="update.php" type="submit" value="編集する">
                        <input class="btn btn-outline-danger" formaction="delete.php" type="submit" value="削除する">
                        <input class="btn btn-outline-warning" type="reset" value="元に戻す">
                        <a class="btn btn-primary" href="index.php">一覧へ</a>
                    </div>
                </div>
            </form>
        </div>    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
    </div>
    <?php require('html/footer.html'); ?>
</body>
</html>