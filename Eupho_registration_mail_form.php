<?php

//データベースへの接続
$dsn = "データベース名";
$user = "ユーザー名";
$password = "パスワード";
$pdo = new PDO($dsn , $user , $password);

//pre_memberテーブル作成
$sql = "CREATE TABLE pre_member (
id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
urltoken VARCHAR(128) NOT NULL,
mail VARCHAR(50) NOT NULL,
date DATETIME NOT NULL,
flag TINYINT(1) NOT NULL DEFAULT 0
)ENGINE=InnoDB DEFAULT CHARACTER SET=utf8";
$pdo->query($sql);


session_start();


header("Content-type: text/html; charset=utf-8");

//クロスサイトリクエストフォージェリ（CSRF）対策

//トークンをセッションにセット
function setToken(){
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;
}

//トークンをセッションから取得
function checkToken(){
    //セッションが空か生成したトークンと異なるトークンでPOSTされたときは不正アクセス
    if(empty($_SESSIOIN['token']) || ($_SESSION['token'] != $_POST['token'])){
        echo '不正なPOSTが行われました', PHP_EOL;
        exit;
    }
}
 
//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');
 
?>
 
<!DOCTYPE html>
<html>
<head>
	<title>メール登録画面</title>
	<meta charset="utf-8">
	 <link rel="stylesheet" href="stylesheet3.css">
</head>
<body>
	<header>
		<div class="header-logo">Euphoria</div>
		<div class="header-list">
			<a href="Eupho_members.php">運営</a>
			<a href="Eupho_practice.php">練習</a>
			<a href="Eupho_attendance.php">出欠</a>
			<a href="Eupho_user.php">メンバー表</a>
			<a href="Eupho_pic.php">写真</a>
			<a href="Eupho_movie.php">動画</a>
			<a href="Eupho_home.php">ログアウト</a>
		</div>
	</header>
	
	<div class="main_header">
		<a href="Eupho_user.php">メンバー表</a>
		<a href="Eupho_introduction.php">自己紹介</a>
		<a href="Eupho_edit_register.php">編集</a>
		<a href="Eupho_registration_mail_form.php">メールアドレス登録</a>
	</div>
	
	<div class="main">
		<h1>メール登録画面</h1>
		<form action="Eupho_registration_mail_check.php" method="post">
 			<p>メールアドレス：<input type="text" name="mail" size="50"></p>
 			 
			<input type="hidden" name="token" value="<?=$token?>">
			<input type="submit" value="登録する">
		</form>
	</div>
	
	<footer>
		<div class="footer-logo">Euphoria</div>
		<div class="footer-list">
			<p><a href="Eupho_members.php">運営</a></p>
			<p><a href="Eupho_practice.php">練習</a></p>
			<p><a href="Eupho_attendance.php">出欠</a></p>
			<p><a href="Eupho_user.php">メンバー表</a></p>
			<p><a href="Eupho_user.php">メンバー表</a></p>
			<p><a href="Eupho_pic.php">写真</a></p>
			<p><a href="Eupho_movie.php">動画</a></p>
		</div>
	</footer>
 
	</form>
 
</body>
</html>

