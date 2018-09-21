<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8">
	<title>Mission</title>
	 <link rel="stylesheet" href="stylesheet1.css">
</head>

<body>
	<header>
		<div class="header-logo">Euphoria</div>
		<div class="header-list">
			<ul>
				<a href="Eupho_home.php">HOME</a>
				<a href="Eupho_about.php">ABOUT</a>
				<a href="Eupho_schedule.php">SCHEDULE</a>
				<a href="Eupho_gallery.php">GALLERY</a>
				<a href="Eupho_location.php">LOCATION</a>
				<a href="Eupho_event.php">EVENT</a>
				<a href="Eupho_contact.php">CONTACT</a>
				<a href="Eupho_members_log.php">MEMBER</a>
			</ul>
		</div>
	</header>
	
	<div class="main">
		<h1>メンバーズページにログイン</h1>
		<form action="Eupho_members_log_check.php" method="post">
			ユーザー名<br>
			<input type="text" name="username">
			<br>パスワード<br>
			<input type="password" name="password">
			<br>
			<input type="submit" value="ログイン">
		
	</div>
	
	<footer>
		<div class="footer-logo">Euphoria</div>
	</footer>
</body>
</html>