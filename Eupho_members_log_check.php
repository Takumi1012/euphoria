<?php
//文字列にある半角全角スペースを削除する関数
function spaceTrim ($str) {
	$str = str_replace(array(" " , "　") , "" , $str);
	return $str;
}
$errors = array();      //エラーメッセージの初期化

if (empty($_POST["username"] ) && (empty($_POST["password"] ))) {
	header("Location: http://tt-18.99sv-coco.com/Eupho_members_log.php");
	exit();
} else {
	//POSTされたデータを各変数にいれる
	$username = isset($_POST["username"]) ? $_POST["username"] : NULL;
	$password = isset($_POST["password"]) ? $_POST["password"] : NULL;
	//入力された文字列にある半角全角スペースを削除する
	$username = spaceTrim($username);
	$password = spaceTrim($password);
	
	//ユーザーネーム入力判定
	if ($username == "") {
		$errors['username'] = "ユーザー名が入力されていません";
	}
	//パスワード入力判定
	if ($password == "") {
		$errors['password'] = "パスワードが入力されていません";
	}
}

//エラーがなければ実行する
if (count($errors) ==0) {
	if ($username == "Euphoria18" && $password == "volley") {
		header("Location: http://tt-18.99sv-coco.com/Eupho_members.php");
		exit();
	} else {
		$errors['pass'] = "ユーザー名及びパスワードが一致しません";
	}
} 
?>


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
		<?php
		if (count($errors) > 0) {
			foreach ($errors as $error) {
				echo "<p>".$error."</p>";
			}
		}
		?>
		<input type="button" value="戻る" onClick="history.back()">
		
	</div>
	
	<footer>
		<div class="footer-logo">Euphoria</div>
	</footer>
</body>
</html>