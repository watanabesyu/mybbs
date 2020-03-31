<?php
try {
    require_once(__DIR__ .'/mybbs_db.php');
    function h($s) {
      return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
    }  
    $sql = $pdo->prepare('INSERT INTO board_table VALUES(null,?,?,null,?)');
    $sql->execute([$_POST['name'],$_POST['title'],$_POST['content']]);
} catch (Exception $e) {
    echo 'エラーが発生しました。:' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>投稿完了</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body> 
  <?php require_once('html/header.html'); ?>
  <div class="text-center"> 
      <div class="jumbotron">
        <h1 class="display-4">WatanabeBBS</h1>
          <h2 class="lead"></h2>
            <hr class="my-4">
          <h3>新しく投稿しました</h3><br>
          <a class="btn btn-primary btn-lg" href="index.php" role="button">投稿一覧へ</a>
      </div>
    </div>
  <div class="container">
  </div>       
  </div>
  <?php require('html/footer.html'); ?>
</body>
</html>