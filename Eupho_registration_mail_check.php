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
		return $pdo;
	}catch (PDOException $e){
	    	print('Error:'.$e->getMessage());
	    	die();
	}
}
 

//データベース接続
$pdo = db_connect();

//エラーメッセージの初期化
$errors = array();

if(empty($_POST)) {
	header("Location: Eupho_registration_mail_form.php");
	exit();
}else{
	//POSTされたデータを変数に入れる
	$mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;
	
	//memberテーブルに同じメールアドレスがあるかどうか
	$sql="SELECT  EXISTS (SELECT * FROM member WHERE mail='$mail') ";
	$mail_member=$pdo->query($sql);
	$mail_member=$mail_member->fetchAll();
	
	//メール入力判定
	if ($mail == NULL){
		$errors['mail'] = "メールが入力されていません。";
	}else{
		if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)){
			$errors['mail_check'] = "メールアドレスの形式が正しくありません。";
		}elseif($mail_member[0][0] == 1){
			$errors['member_check'] = "このメールアドレスはすでに利用されております。";
		}
	}
}


if (count($errors) === 0){
	
	$urltoken = hash('sha256',uniqid(rand(),1));
	$url = "http://tt-18.99sv-coco.com//Eupho_registration_form.php"."?urltoken=".$urltoken;
	
	//ここでデータベースに登録する
	try{
		//例外処理を投げる（スロー）ようにする
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$statement = $pdo->prepare("INSERT INTO pre_member (urltoken,mail,date) VALUES (:urltoken,:mail,now() )");
		
		//プレースホルダへ実際の値を設定する
		$statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
		$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
		$statement->execute();
			
		//データベース接続切断
		$pdo = null;	
		
	}catch (PDOException $e){
		print('Error:'.$e->getMessage());
		die();
	}
	
	//メールの宛先
	$mailTo = $mail;
 
	//Return-Path(返信先)に指定するメールアドレス
	$returnMail = 'Eupho@volley.com';
 
	$name = "ゆーふぉりあ";
	$mail = 'Eupho@volley.com';
	$subject = "【Euphoria】メンバー登録用URLのお知らせ";

	$body = "24時間以内に下記のURLからご登録下さい。".$url;

	//メールの言語を日本語に設定する
	mb_language('ja');
	mb_internal_encoding('UTF-8');
 
	//Fromヘッダーを作成
	$header = 'From: ' . mb_encode_mimeheader($name). ' <' . $mail. '>';
	
	if (mb_send_mail($mailTo, $subject, $body, $header, "-f  $returnMail")) {    //メール関数 //fの後はエラーが起きた際の送信先を示す
	
		//クッキーの削除
		if (isset($_COOKIE['PHPSESSID'])) {
    		setcookie('PHPSESSID', '', time() - 1800, '/');
		}
		
	 	//セッション変数を全て解除
		$_SESSION = array();
		
 		//セッションを破棄する
 		session_destroy();
 	
 		$message = "メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい。";
 	
	 } else {
		$errors['mail_error'] = "メールの送信に失敗しました。";
	}	
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>メール確認画面</title>
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
		<a href="Eupho_register.php">自己紹介</a>
		<a href="Eupho_edit_register.php">編集</a>
		<a href="Eupho_registration_mail_form.php">メールアドレス登録</a>
	</div>
	
	<div class="main">
		<h1>メール確認画面</h1>

<?php if (count($errors) === 0){ ?>     

		<p><?=$message?></p>

		<p>↓このURLが記載されたメールが届きます。</p>
		<a href="<?=$url?>"><?=$url?></a>

<?php }else{      

	foreach($errors as $value){                  
		echo "<p>".$value."</p>";
	}
?>

		<input type="button" value="戻る" onClick="history.back()">

<?php } ?>
	</div>
	
	<footer>
		<div class="footer-logo">Euphoria</div>
		<div class="footer-list">
			<p><a href="Eupho_members.php">運営</a></p>
			<p><a href="Eupho_practice.php">練習</a></p>
			<p><a href="Eupho_attendance.php">出欠</a></p>
			<p><a href="Eupho_user.php">メンバー表</a></p>
			<p><a href="Eupho_pic.php">写真</a></p>
			<p><a href="Eupho_movie.php">動画</a></p>
		</div>
	</footer>
 
</body>
</html>