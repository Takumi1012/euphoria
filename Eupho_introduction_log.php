
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
		<a href="Eupho_introduction_log.php">自己紹介</a>
		<a href="Eupho_edit_register.php">編集</a>
		<a href="Eupho_registration_mail_form.php">メールアドレス登録</a>
	</div>
	
	<div class="main">
		<h3>メールアドレスとパスワードを入力してください
		<form action="Eupho_introduction.php" method="post">
			<br>メールアドレス:
			<input type="text" name="mail" required>
			<br>パスワード:
			<input type="text" name="pass">
			<br><input type="submit" value="送信">
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