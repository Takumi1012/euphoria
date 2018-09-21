<?php

session_start();

header("Content-type: text/html; charset=utf-8");

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//データベースに接続する関数
function db_connect(){
	$dsn = "データベース名";
	$user = "ユーザー名";
	$password = "パスワード";
	
	try{
		$pdo = new PDO($dsn, $user, $password);
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

if(empty($_GET)) {
	header("Location: Eupho_registration_mail_form.php");
	exit();
}else{
	//GETデータを変数に入れる
	$urltoken = isset($_GET[urltoken]) ? $_GET[urltoken] : NULL;
	//メール入力判定
	if ($urltoken == NULL){
		$errors['urltoken'] = "もう一度登録をやりなおして下さい。";
	}else{
		try{
			//例外処理を投げる（スロー）ようにする
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//flagが0の未登録者・仮登録日から24時間以内
			$statement = $dbh->prepare("SELECT mail FROM pre_member WHERE urltoken=(:urltoken) AND flag =0 AND date > now() - interval 24 hour");
			$statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
			$statement->execute();
			
			//レコード件数取得
			$row_count = $statement->rowCount();
			
			//24時間以内に仮登録され、本登録されていないトークンの場合
			if( $row_count ==1){
				$mail_array = $statement->fetch();
				$mail = $mail_array[mail];
				$_SESSION['mail'] = $mail;
			}else{
				$errors['urltoken_timeover'] = "このURLはご利用できません。有効期限が過ぎた等の問題があります。もう一度登録をやりなおして下さい。";
			}
			
			//データベース接続切断
			$dbh = null;
			
		} catch (PDOException $e){
			print('Error:'.$e->getMessage());
			die();
		}
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>会員登録画面</title>
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
		<a href="Eupho_resistration_mail_form.php">メールアドレス登録</a>
	</div>
	
	<div class="main">
		<h1>会員登録画面</h1>

<?php if (count($errors) === 0): ?>

		<form action="Eupho_registration_check.php" method="post">

			<p>メールアドレス：<?=htmlspecialchars($mail, ENT_QUOTES, 'UTF-8')?></p>
			<p>パスワード：<input type="text" name="password"></p>
 
			<input type="hidden" name="token" value="<?=$token?>">
			<input type="submit" value="確認する">
 
		</form>
 
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