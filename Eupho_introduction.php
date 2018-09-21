<?php
//データベースへの接続
$dsn = "データベース名";
$user = "ユーザー名";
$password = "パスワード";
$pdo = new PDO($dsn , $user , $password);

//イントロダクションテーブル作成
$sql = "CREATE TABLE introduction (id INT auto_increment not null primary key,"
."date char (32) , "
."name char (32) , "
."nickname char(32) , "
."email varchar (35) not null ,"
."hometown char(32) , "
."university char(32) , "
."password1 varchar (60) not null , "
."password2 varchar (60) not null "
.");";
$pdo->query($sql);

//前後にある半角全角スペースを削除する関数
function spaceTrim ($str) {
	// 行頭
	$str = preg_replace('/^[ 　]+/u', '', $str);
	// 末尾
	$str = preg_replace('/[ 　]+$/u', '', $str);
	return $str;
}

//エラーメッセージの初期化
$errors = array();

if(empty($_POST)){
	header("Location:Eupho_introduction_log.php");
	exit();
}else{
	//POSTされたデータを各変数に入れる
	$mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;
	$pass = isset($_POST['pass']) ? $_POST['pass'] : NULL;
	
	//前後にある半角全角スペースを削除
	$mail = spaceTrim($mail);
	$pass = spaceTrim($pass);
	
	
?>
<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8">
	<title>Mission</title>
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
		<a href="Eupho_register.php">自己紹介</a>
		<a href="Eupho_edit_register.php">編集</a>
		<a href="Eupho_registration_mail_form.php">メールアドレス登録</a>
	</div>
	
	<div class="main">
	

		<h3>名前・メールアドレス・パスワード・パスワード確認は必須
		<form action="Eupho_user.php" method="post">
			<br>名前:
			<input type="text" name="name" required>
			<br>ニックネーム:
			<input type="text" name="nickname">
			<br>メールアドレス:
			<input type="text" name="email" required>
			<br>出身:
			<input type="text" name="hometown">
			<br>大学:
			<input type="text" name="university">
			<br>パスワード:
			<input type="password" name="password1" required>
			<br>パスワード確認:
			<input type="password" name="password2" required>
			<br><input type="submit" name="保存">
		</form>
	</div>
	
	<footer>
		<div class="footer-logo">Euphoria</div>
		<div class="footer-list">
			<ul class="footer-list">
				<li><a href="Eupho_members.php">運営</a></li>
				<li><a href="Eupho_practice.php">練習</a></li>
				<li><a href="Eupho_attendance.php">出欠</a></li>
				<li><a href="Eupho_user.php">メンバー表</a></li>
			</ul>
		</div>
	</footer>
</body>
</html>