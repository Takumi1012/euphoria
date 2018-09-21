<?php

session_start();

header("Content-type: text/html; charset=utf-8");

//クロスサイトリクエストフォージェリ（CSRF）対策のトークン判定
if ($_POST['token'] != $_SESSION['token']){
	echo "不正アクセスの可能性あり";
	exit();
}

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//データベースに接続する関数
function db_connect(){
	$dsn = "データベース名";
	$user = "ユーザー名";
	$password = "パスワード";
	
	try{
		$pdo = new PDO($dsn, $user, $password);
		//memberテーブル作成
		$sql = "CREATE TABLE member (
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
		."mail VARCHAR(50) NOT NULL,"
		."password VARCHAR(128) NOT NULL,"
		."flag TINYINT(1) NOT NULL DEFAULT 1"
		.")ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;";
		$pdo->query($sql);
		return $pdo;
	}catch (PDOException $e){
	    	print('Error:'.$e->getMessage());
	    	die();
	}
}

//データベース接続
$dbh = db_connect();


//エラーメッセージの初期化
$errors = array();

if(empty($_POST)) {
	header("Location: Eupho_registration_mail_form.php");
	exit();
}

$mail = $_SESSION['mail'];

//パスワードのハッシュ化
$password_hash = hash(‘sha1’, $_SESSION['password']);

//ここでデータベースに登録する
try{
	//例外処理を投げる（スロー）ようにする
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//トランザクション開始
	$dbh->beginTransaction();
	
	//memberテーブルに本登録する
	$statement = $dbh->prepare("INSERT INTO member (mail,password) VALUES (:mail,:password_hash)");
	//プレースホルダへ実際の値を設定する
	$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
	$statement->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
	$statement->execute();
		
	//pre_memberのflagを1にする
	$statement = $dbh->prepare("UPDATE pre_member SET flag=1 WHERE mail=(:mail)");
	//プレースホルダへ実際の値を設定する
	$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
	$statement->execute();
	
	// トランザクション完了（コミット）
	$dbh->commit();
		
	//データベース接続切断
	$dbh = null;
	
	//セッション変数を全て解除
	$_SESSION = array();
	
	//セッションクッキーの削除・sessionidとの関係を探れ。つまりはじめのsesssionidを名前でやる
	if (isset($_COOKIE["PHPSESSID"])) {
    		setcookie("PHPSESSID", '', time() - 1800, '/');
	}
	
 	//セッションを破棄する
 	session_destroy();
	
	//メールの宛先
	$mailTo = $mail;
 
	//Return-Path(返信先)に指定するメールアドレス
	$returnMail = 'Eupho@volley.com';
 
	$name = "ゆーふぉりあ";
	$mail = 'Eupho@volley.com';
	$subject = "【Euphoria】メンバー登録完了のお知らせ";

	$body = "ゆーふぉりあへようこそ";

	//メールの言語を日本語に設定する
	mb_language('ja');
	mb_internal_encoding('UTF-8');
 
	//Fromヘッダーを作成
	$header = 'From: ' . mb_encode_mimeheader($name). ' <' . $mail. '>';
	
	mb_send_mail($mailTo, $subject, $body, $header, "-f  $returnMail");
		
}catch (PDOException $e){
	//トランザクション取り消し（ロールバック）
	$dbh->rollBack();
	$errors['error'] = "もう一度やりなおして下さい。";
	print('Error:'.$e->getMessage());
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>会員登録完了画面</title>
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

<?php if (count($errors) === 0): ?>

		<h1>会員登録完了画面</h1>

		<p>登録完了いたしました。ログイン画面からどうぞ。</p>
		<p><a href="">ログイン画面（未リンク）</a></p>

<?php elseif(count($errors) > 0): ?>

<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>

<?php endif; ?>

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
 
 
</body>
</html>