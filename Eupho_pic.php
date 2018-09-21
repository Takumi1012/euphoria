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
			<a href="Eupho_user.php">メンバー表</a>
			<a href="Eupho_pic.php">写真</a>
			<a href="Eupho_movie.php">動画</a>
			<a href="Eupho_home.php">ログアウト</a>
		</div>
	</header>
	
	<div class="main">
	
<?php

try{
	if(isset($_FILES)&& isset($_FILES['upfile']) && is_uploaded_file($_FILES['upfile']['tmp_name'])){
		
		//uploadというファイルがあるか否か
		if(!file_exists('upload')){
        mkdir('upload');
    	}
    	
    	$filename="upload/".$_FILES['file']['name'];
    	
		if(move_uploaded_file($_FILES['file']['tmp_name'],$filename)){
			$msg=$filename."のアップロードに成功しました";
		}else{
			$msg="アップロードに失敗しました";
		}
	}
} catch(Exception $e) {
	echo 'エラー:',$e->getMessage().PHP_EOL;     //PHP_EOLは改行を表す
}

var_dump($FILES['file']['tmp_name']);
?>
		<form action="Eupho_pic.php" method="post" enctype="multipart/form-data">
			<input type="file" name="upfile">
			<input type="submit" value="アップロード">
<?php
if(isset($msg)&&$msg==true){
	echo "<p>".$msg."<p>";
?>
			<a href="Eupho_pic.php">戻る</a>
<?php } ?>
		</form>			
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
