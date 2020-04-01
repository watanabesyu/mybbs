<?php
//DBに接続
    //本番
    //$pdo = new PDO ('mysql:dbname=ppftech_db3;host=;carset=utf8','','');
    //ローカル
    //DB:mysql DBname:mybbs host:localhost carset:utf8mb4_general_ci user:mybbs pass:mybbs
    $pdo = new PDO ('mysql:dbname=mybbs;host=localhost;carset=utf8mb4_general_ci','mybbs','mybbs');
?>