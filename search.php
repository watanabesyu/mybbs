<?php
  try {
    //db接続
    require('mybbs_db.php');
    function h($s) {
      return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
    }  
    //検索処理
    $stmt = $pdo->prepare('SELECT * FROM board_table WHERE name = :name OR title = :title OR date = :date');
    $stmt->execute(array(':id' => $_POST["id"]));
    $result = 0;
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //ページング処理
    //現在のページを取得
    if (isset($_GET['page'])) {
      $nowPage = (int)$_GET['page'];
    } else {
      $nowPage = 1;
    }
    //開始ページを取得
    if ($nowPage > 1) {
      $start = ($nowPage * 5) - 5;
    }else {
      $start = 0;
    }
    //5件のデータを降順(投稿日時)で取得
    $stmt = $pdo->query("SELECT * FROM board_table ORDER BY date DESC LIMIT {$start}, 5;");
    $stmt->execute();
    $stmt = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //テーブルのデータ件数を取得する
    $page_num = $pdo->prepare("SELECT COUNT(*) FROM board_table");
    $page_num->execute();
    $page_num = $page_num->fetchColumn();
  
    //最大ページ数を取得する
    $maxPage = ceil($page_num / 5);

  }catch (Exception $e) {
    echo 'エラーが発生しました。:' . $e->getMessage();
  }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>MyBBS</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="css/watanabe.css" type="text/css">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
  <!--header読み込み--> 
  <?php require('html/header.html'); ?>
    <div class="text-center"> 
      <div class="jumbotron">
        <h1 class="display-4">WatanabeBBS</h1>
          <h2 class="lead">なにかここにかける</h2>
            <hr class="my-4">
          <h3>背景はフリー画像の滝さん</h3><br><br><br>
          <a class="btn btn-primary btn-lg" href="new.php" role="button">新規投稿</a>
      </div>
    </div>
    <div class="container">
    <!-- 検索フォーム -->
    <form method="get" name="new" action="search.php" onSubmit="return check()">
    <div class="form-group clearfix">
      <div class="float-right">
        <input type="search" autocomplete="on" rows="10">
        <input class="btn btn-dark" type="submit" value="検索">
      </div>
    </div>  
      <!-- radioボタン -->
    <div class="form-group">
      <div class="float-right clearfix">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="search[]" value="部分一致">
          <label class="form-check-label">部分一致</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="search[]" value="完全一致">
          <label class="form-check-label">完全一致</label>
        </div>
      </div>
    </div>    
    </form>
    <!--tableを表示-->
    <div class="table-responsive overflow-y:scroll">
      <table class="table table-striped table-bordered table-hover">
        <!-- headerを表示 -->
        <thead class="thead-dark">   
          <tr class ="text-center">
            <th scope="col"><i class="fas fa-signature"></i>名前</th>
            <th scope="col"><i class="fas fa-file-signature"></i>タイトル</th>
            <th scope="col"><i class="far fa-clock"></i>投稿日付</th>
            <th scope="col"><i class="far fa-edit"></i></th>
          </tr>   
        </thead>
        <tbody>
        <!--foreachでboard_tableを表示-->
        <?php foreach ($stmt as $user): ?>
          <tr>
            <td><?php echo h($user['name']) ?></td>
            <td><a href="show.php?id=<?= $user['id'] ?>"><?= h($user['title']) ?></a></td>
            <td><?= $user["date"] ?></td>
            <td>
              <a class="btn btn-outline-success" href="edit.php?id=<?= $user['id'] ?>">編集</a>
            </td>
          </tr>
         <?php endforeach ?> 
        </tbody>
      </table>
      </div>
        <!--新規作成ページへ-->
        <div class="button">
        <a class="btn btn-info" href="new.php">新規投稿</a>
        </div>
        <!--ページング表示-->
      <nav aria-label="...">          
        <ul class="pagination justify-content-center">
          <!-- $nowPage - 1で前のページに移動 -->
          <!-- $nowPageが1の時はクリックできないようにする -->
          <?php if($nowPage < 2) { ?>
            <li class="page-item">
              <a class="page-link">最初のページです</a>
            </li>
          <?php }else { ?>
            <li class="page-item"><a class="page-link" href="?page= 1">最初</a></li>
            <li class="page-item"><a class="page-link" href="?page=<?= $nowPage - 1 ?>">前へ</a></li>
          <?php } ?>
            <!-- 現在のページから-2件まで表示する -->
            <?php for ($y = $nowPage - 3; $y < $nowPage; $y++): ?>       
              <!-- マイナスの数値になった場合の処理 -->
              <?php if($y <= 0) { 
                continue; 
              }else {?>
                <li class="page-item">
                  <a class="page-link" href="?page=<?php echo $y ?>"><?php echo $y; ?></a>
                </li>
              <?php } ?>
            <?php endfor ?>
              <!-- 現在ページはリンクにしない -->
              <li class="page-item">
                <a class="page-link"><?php echo $nowPage ?></a>
              </li>
            <!-- 現在のページから+2件まで表示する -->  
            <?php for ($y = $nowPage + 1; $y <= $nowPage + 3; $y++): ?>       
              <!--現在のページが最大ページ数を超えたら処理を終える-->
              <?php if($y - 1 >= $maxPage){
                break;
              }else { ?>
                <li class="page-item">
                  <a class="page-link" href="?page=<?php echo $y ?>"><?php echo $y; ?></a>
                </li>
              <?php } ?>  
            <?php endfor ?>
          <!-- $nowPage + 1で次のページに移動 -->
          <!-- $nowPageが最後のページの時はクリックできないようにする -->
          <?php if($nowPage >= $maxPage) { ?>
            <li class="page-item">
              <a class="page-link">最後のページです</a>
            </li>
          <?php }else { ?>
            <li class="page-item"><a class="page-link" href="?page=<?php echo $nowPage + 1 ?>"> 次へ</a></li>
            <li class="page-item"><a class="page-link" href="?page=<?php echo $maxPage ?>">最後</a></li>
          <?php } ?>
        </ul>
      </nav>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
    <?php require('html/footer.html'); ?>
  </body>
</html>