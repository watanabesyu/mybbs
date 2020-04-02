<?php
//バリデーション
//ソートの挙動がおかしい
//最大表示数まわりも要改修
//ページングと最大表示数とソート同時に使われた時の挙動を考える
  try {
    //db接続
    require('mybbs_db.php');
    function h($s) {
      return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
    }
    //ページング処理
    //表示件数を取得(初期値を10件に)
    if (isset($_POST['mv'])) {
      $max_view = (int)$_POST['mv'];
    } else {
      $max_view = 10;
    }
    //現在のページを取得
    if (isset($_GET['page'])) {
      $nowPage = (int)$_GET['page'];
    } else {
      $nowPage = 1;
    }
    //開始ページを取得
    if ($nowPage > 1) {
      $start = ($nowPage * $max_view) - $max_view;
    }else {
      $start = 0;
    }
    //10件のデータを降順(投稿日時)で取得
    //検索フォームが使われた場合
    if(isset($_POST["sw"])){
      $search = $_POST["sw"];
      $stmt = $pdo->prepare("SELECT * FROM board_table WHERE title LIKE ? OR name LIKE ? ORDER BY date DESC LIMIT {$start}, {$max_view};");
      //execute実行前に%と検索ワードを結合する。
      $stmt->execute(array(0 => '%'.$search.'%',1 => '%'.$search.'%'));
      $stmt = $stmt->fetchALL(PDO::FETCH_ASSOC);
      //テーブルのデータ件数を取得する
      $page_num = $pdo->prepare("SELECT COUNT(*) FROM board_table WHERE title LIKE ? OR name LIKE ?;");
      $page_num->execute(array(0 => '%'.$search.'%',1 => '%'.$search.'%'));
      $page_num = $page_num->fetchColumn();
      //最大ページ数を取得する
      $maxPage = ceil($page_num / $max_view);
    
      //ts(table_sort)機能処理
    }else {
      //elseif(isset($_POST['ts'])){}
      //変数名:例nsu(name(カラム名) sort up(asc OR desc))
      if(isset($_POST['ts'])){
        $table_sort = $_POST['ts'];
      }else{
        $table_sort = '';
      }
      $sort_option = '';
      switch($table_sort){
        case 'nsu':
          $sort_option = 'name';
          break;
        case 'nsd':
          $sort_option = 'name DESC';
          break;
        case 'tsu':
          $sort_option = 'title';
          break;
        case 'tsd':
          $sort_option = 'title DESC';
          break;
        case 'dsu':
          $sort_option = 'date';
          break;
        default:
          $sort_option = 'date DESC';
          break;
      }
      $stmt = $pdo->query("SELECT * FROM board_table ORDER BY {$sort_option} LIMIT {$start},{$max_view}");
      $stmt = $stmt->fetchALL(PDO::FETCH_ASSOC);
      //テーブルのデータ件数を取得する
      $page_num = $pdo->query("SELECT COUNT(*) FROM board_table");
      $page_num = $page_num->fetchColumn();
      //最大ページ数を取得する
      $maxPage = ceil($page_num / $max_view); 
    } 
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
    <script type="text/javascript"> 
      function check(){
        var flag = 0;
        // 入力check
        if(document.search.sw.value == ""){ // 入力をチェック
          flag = 1;
        }else{ // 入力があった場合文字数を数える
            nv = document.search.sw.value;
            nl = nv.length;
        }
        // 設定終了
        if(flag){
          window.alert('検索ワードが入力されていません'); // 入力漏れがあれば警告ダイアログを表示
          return false; // 送信を中止
        }else if(nl > 15){ // 入力文字数が多いと警告ダイアログを表示
            window.alert('15文字以下で入力してください');
            return false; // 送信を中止 
        }else{
          return true; // 送信を実行
        }  
      }
      function checkView(){
        var flag = 0;
        // 入力check
        if(document.view.mv.value == '選択...'){ // 入力をチェック
          flag = 1;
        }
        // 設定終了
        if(flag){
          window.alert('表示件数を選択してください'); // 入力漏れがあれば警告ダイアログを表示
          return false; // 送信を中止
        }else{
          return true; // 送信を実行
        }  
      }  
    </script>
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
          <a class="btn btn-primary btn-lg" href="index.php" role="button">投稿一覧</a>
      </div>
    </div>
    <div class="container">
    <!-- 検索フォーム -->
    <?php if(isset($_POST['sw'])){ ?>
      <?= "<h3>検索ワード:  ".h($_POST["sw"])."</h3>" ?>
    <?php } ?>
    <form method="post" name="search" onSubmit="return check()">
    <div class="form-group clearfix">
      <div class="float-right">
        <input class="col-unset-6 "type="text" name="sw">
        <input class="btn btn-dark" type="submit" name="kensaku" value="検索">
      </div>
    </div>
    </form>
    <!-- 最大表示数 -->
    <form method="post" class="form-inline" name="view" onSubmit="return checkView()">
      <label class="my-1 mr-2" for="inlineFormCustomSelect">表示件数:</label>
      <select class="custom-select my-1 mr-sm-2" id="inlineFormCustomSelect" name="mv">
        <option selected>選択...</option>
        <?php for($i=10; $i <= 100; $i += 10): ?>
          <option value=<?= $i ?>><?= $i ?></option>
        <?php endfor ?>
      </select>
      <button class="btn btn-outline-secondary btn-md" type="submit" name="select" value="view_num">更新</button>
    </form>
    <!--tableを表示-->
    <div class="table-responsive overflow-y:scroll">
      <table id="table1" class="table table-striped table-bordered table-hover">
        <!-- theadeを表示 -->
        <thead class="thead-dark">
         <form method="post">
          <tr class ="text-center">
            <th><i class="fas fa-signature"></i>名前 
              <button class="btn btn-outline-secondary btn-sm" type="submit" name="ts" value="nsu">↑</button>
              <button class="btn btn-outline-secondary btn-sm" type="submit" name="ts" value="nsd">↓</button>
            </th>
            <th><i class="fas fa-file-signature"></i>タイトル 
              <button class="btn btn-outline-secondary btn-sm" type="submit" name="ts" value="tsu">↑</button>
              <button class="btn btn-outline-secondary btn-sm" type="submit" name="ts" value="tsd">↓</button>
            </th>
            <th><i class="far fa-clock"></i>投稿日付 
              <button class="btn btn-outline-secondary btn-sm" type="submit" name="ts" value="dsu">↑</button>
              <button class="btn btn-outline-secondary btn-sm" type="submit" name="ts" value="dsd">↓</button>
            </th>
            <th><i class="far fa-edit"></i> 
            </th>
          </tr>
          </form>
        </thead>
        <tbody>
        <!--foreachでboard_tableを表示-->
          <?php foreach ($stmt as $table): ?>
          <tr>
            <td class ="text-left"><?= h($table['name']) ?></td>
            <td class ="text-center"><a href="show.php?id=<?= $table['id'] ?>"><?= h($table['title']) ?></a></td>
            <td class ="text-right"><?= $table["date"] ?></td>
            <td class ="text-center">
              <a class="btn btn-outline-success" href="edit.php?id=<?= $table['id'] ?>">編集</a>
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
              }else { ?>
                <li class="page-item">
                  <a class="page-link" href="?page=<?= $y ?>"><?= $y; ?></a>
                </li>
              <?php } ?>
            <?php endfor ?>
              <!-- 現在ページはリンクにしない -->
              <li class="page-item">
                <a class="page-link"><?= $nowPage ?></a>
              </li>
            <!-- 現在のページから+2件まで表示する -->  
            <?php for ($y = $nowPage + 1; $y <= $nowPage + 3; $y++): ?>       
              <!--現在のページが最大ページ数を超えたら処理を終える-->
              <?php if($y - 1 >= $maxPage){
                break;
              }else { ?>
                <li class="page-item">
                  <a class="page-link" href="?page=<?= $y ?>"><?= $y; ?></a>
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
            <li class="page-item"><a class="page-link" href="?page=<?= $nowPage + 1 ?>"> 次へ</a></li>
            <li class="page-item"><a class="page-link" href="?page=<?= $maxPage ?>">最後</a></li>
          <?php } ?>
        </ul>
      </nav>
    </div>
<?php require('html/footer.html'); ?>
<script src="//code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/extras/jquery.tablesorter.pager.min.js"></script>
  </body>
</html>