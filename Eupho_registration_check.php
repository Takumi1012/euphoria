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

if(empty($_POST)) {
	header("Location: Eupho_registration_mail_form.php");
	exit();
}else{
	//POSTされたデータを各変数に入れる
	$password = isset($_POST['password']) ? $_POST['password'] : NULL;
	
	//前後にある半角全角スペースを削除
	$password = spaceTrim($password);
	
	//パスワード入力判定
	if ($password == ''):
		$errors['password'] = "パスワードが入力されていません。";
	elseif(!preg_match('/^[0-9a-zA-Z]{5,30}$/', $_POST["password"])):
		$errors['password_length'] = "パスワードは半角英数字の5文字以上30文字以下で入力して下さい。";
	else:
		$password_hide = str_repeat('*', strlen($password));
	endif;
	
}

//エラーが無ければセッションに登録
if(count($errors) === 0){
	$_SESSION['password'] = $password;
}

?>

<!DOCTYPE html>
<html>
<head>
<title>会員登録確認画面</title>
<meta charset="utf-8">
</head>
<body>
<h1>会員登録確認画面</h1>
 
<?php if (count($errors) === 0): ?>


<form action="Eupho_registration_insert.php" method="post">

<p>メールアドレス：<?=htmlspecialchars($_SESSION['mail'], ENT_QUOTES)?></p>
<p>パスワード：<?=$password_hide?></p>

<input type="button" value="戻る" onClick="history.back()">
<input type="hidden" name="token" value="<?=$_POST['token']?>">
<input type="submit" value="登録する">

</form>

<?php elseif(count($errors) > 0): ?>

<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>

<input type="button" value="戻る" onClick="history.back()">

<?php endif; ?>
 
</body>
</html>