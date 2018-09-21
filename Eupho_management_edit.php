<?php
//データベースへの接続
$dsn = "データベース名";
$user = "ユーザー名";
$password = "パスワード";
$pdo = new PDO($dsn , $user , $password);

//削除する投稿を表示させる
$sql = "SELECT * FROM management";
$results = $pdo -> query($sql) -> fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
$results = $results[$_POST['id2']] ;
$data1 = $results['name'];
$data2 = $results['comment'];
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Mission</title>
  <link rel="stylesheet" href="stylesheet2.css">
</head>

<body>
	<header>
		<div class="header-logo">Euphoria</div>
		<div class="header-list">
			<a href="Eupho_members.php">運営</a>
			<a href="Eupho_practice.php">練習</a>
			<a href="Eupho_attendance.php">出欠</a>
		</div>
	</header>

	<div class="main">
		<br>
		<h3>編集するフォーム</h3>
		<form action=Eupho_members.php method="post" >
			<br>名前<br>
			<input type="text" name="Name" value = "<?php echo $data1; ?>">
			<br>コメント<br>
			<textarea name="Comment" rows="10" cols="60"><?php echo $data2; ?></textarea><br>
			<input type="submit" value="編集" >
			<input type="hidden" name="id3" value="<?=$_POST['id2']?>">
		</form>
		
	<footer>
		<div class="footer-logo">Euphoria</div>
		<div class="footer-list">
			<ul>
				<a>運営</a>
				<a>練習</a>
				<a>出欠</a>
			</ul>
		</div>
	</footer>
</body>
</html>
</body>
</html>

<?php
//入力したデータを表示する
$sql = "SELECT * FROM management ORDER BY id ASC";
$results = $pdo -> query($sql);
foreach ($results as $row) {
	echo "<hr>";
	echo "名前:".$row['name']."<br>";
	echo "コメント:".$row['comment']."<br>";
}
?>
		
	
