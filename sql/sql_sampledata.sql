-- 倍々に空データを追加30行
INSERT INTO board_table () VALUES ();
INSERT INTO board_table (id) SELECT 0 FROM board_table;
INSERT INTO board_table (id) SELECT 0 FROM board_table;
INSERT INTO board_table (id) SELECT 0 FROM board_table;
INSERT INTO board_table (id) SELECT 0 FROM board_table;
INSERT INTO board_table (id) SELECT 0 FROM board_table;
-- ランダムな値に更新
UPDATE board_table SET
  name = CONCAT('テスト', id),
  title = SUBSTRING(MD5(RAND()), 1, 30),
  content = SUBSTRING(MD5(RAND()), 1, 30);
-- comment_table
INSERT INTO comment_table () VALUES ();
INSERT INTO comment_table (id) SELECT 0 FROM comment_table;
INSERT INTO comment_table (id) SELECT 0 FROM comment_table;
INSERT INTO comment_table (id) SELECT 0 FROM comment_table;
INSERT INTO comment_table (id) SELECT 0 FROM comment_table;
INSERT INTO comment_table (id) SELECT 0 FROM comment_table;
-- ランダムな値に更新
UPDATE comment_table SET
  comment_name = CONCAT('コメント', id),
  comment_content = SUBSTRING(MD5(RAND()), 1, 30),
  content_id = CEIL(RAND() * 100);