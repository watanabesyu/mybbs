<?php
    //DBに接続
    try {
        require_once(__DIR__ .'/mybbs_db.php');
        function h($s) {
            return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
        }
        $id = $_GET['id'];
        if(isset($_POST['comment_name']) && isset($_POST['comment'])){
            $name = $_POST['comment_name'];
            $comment = $_POST['comment'];
            //DBに新規投稿を追加
            $sql = $pdo->prepare('INSERT INTO comment_table VALUES(null,?,?,null,?)');
            $sql->execute(array($name,$comment,$id));
            $sql = null;
        }
        //board_tableからidで表示内容を検索
        $stmt = $pdo->prepare("SELECT * FROM board_table WHERE id = ?");
        $stmt->execute(array(0 => $id));
        $result = 0;
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        //comment_tableからidで表示内容を検索
        $stmt2 = $pdo->prepare('SELECT * FROM comment_table WHERE content_id =? ORDER BY comment_date DESC');
        $stmt2->execute(array(0 => $id));
        $stmt2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo 'エラーが発生しました。:' . $e->getMessage();
    } 
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>詳細画面</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/watanabe.css" type="text/css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script type="text/javascript"> 
      function check(){
        var flag = 0;
        // 入力check
        if(document.cf.comment_name.value == null){ // nameの入力をチェック
          flag = 1;
        }else if(document.cf.comment.value == null){ // commentの入力をチェック
          flag = 1;
        }else{
            nv = document.cf.comment_name.value;
            cv = document.cf.comment.value;
            nl = nv.length;
            cl = cv.length;
        }
        // 設定終了
        if(flag){
          window.alert('入力しなさい'); // 入力漏れがあれば警告ダイアログを表示
          return false; // 送信を中止
        }else if(nl > 15){
            window.alert('名前長すぎ');
            return false; // 送信を中止        
        }else if(cl > 50){
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
                <h1 class="display-4">WatanabeBBS</h1>
                <h3>詳細ページ</h3><br><br><br>
                <a class="btn btn-primary btn-lg" href="new.php" role="button">新規投稿</a>
                <a class="btn btn-primary btn-lg" href="index.php" role="button">投稿一覧へ</a>
            </div>
        </div>
    </div>
    <div class="container">
    <div class="text-center row justify-content-center"> 
    <div class="box-black col-sm-8" >
        <h3>投稿者:</h3><h4><?php if (!empty($result['name'])) echo h($result['name'])?></h4>
        <h3>タイトル:</h3><h4><?php if (!empty($result['title'])) echo h($result['title'])?></h4>
        <p><?php if (!empty($result['content'])) echo h($result['content'])?><br></p>
    </div>
    </div>
        <div class= "row">
            <div class="col-sm-6 offset-2">
                <a class="btn btn-outline-success" href="edit.php?id=<?= $result['id'] ?>">投稿を編集する</a><br>
            </div>    
        </div>
            <!--comment一覧-->
            <div class="row">
            <div class="col-sm-6 offset-3">
            <div class="box-comment text-center">
                <div class="box-title">コメント</div>
                    <?php foreach ($stmt2 as $comment): ?>   
                        <h5>投稿者:<?php if(!empty($comment['comment_name'])) echo h($comment['comment_name']) ?></h5>
                        <?php if(!empty($comment['comment_content'])) echo h($comment['comment_content']) ?><br>
                        <h6><?php if(!empty($comment['comment_date'])) echo h($comment['comment_date']) ?></h6>
                    <?php endforeach ?>
                </div>
            </div>
            </div>
            </div> 
        <br>
        <!--comment投稿フォーム-->
        <form method="post" name="cf" onSubmit="return check()">
            <div class="form-group row justify-content-center">
                <div class="col-4">    
                    <h3>コメントを投稿する</h3><br>
                    <lavel>名前</lavel><br>
                        <input type="text" name="comment_name" value=""><br>
                    <lavel>コメント</lavel><br>
                        <textarea name="comment" rows="5" cols="50" wrap="soft"></textarea><br>
                        <input  class="btn btn-outline-secondary" name='comment_button' type="submit" value="コメントする">
                        <input class="btn btn-outline-warning" type="reset" value="リセット">
                </div>    
            </div>
        </form>
    </div>    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
    <?php require('html/footer.html'); ?>
</body>
</html>