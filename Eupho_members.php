<?php
//データベースへの接続
$dsn = "データベース名";
$user = "ユーザー名";
$password = "パスワード";
$pdo = new PDO($dsn , $user , $password);

//運営テーブル作成
$sql = "CREATE TABLE management (id INT auto_increment not null primary key,"
."name char (32) , "
."comment TEXT ,"
."date char (32) "
.");";
$pdo->query($sql);
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
			<a href="Eupho_user.php">メンバー表</a>
			<a href="Eupho_pic.php">写真</a>
			<a href="Eupho_movie.php">動画</a>
			<a href="Eupho_home.php">ログアウト</a>
		</div>
	</header>
	
	<div class="main">
		<br>
		<h3>運営情報</h3>
		<form action=Eupho_members.php method="post" >
			<br>名前<br>
			<input type="text" name="name">
			<br>コメント<br>
			<textarea name="comment" rows="10" cols="60"></textarea><br>
			<input type="submit" name="投稿">
		</form>
			
<?php 
$name = $_POST["name"] ;          //POST送信によるnameの受け取り
$comment = $_POST["comment"] ;    //POST送信によるcontentの受け取り
$date = date( "Y / m / d  H : i : s" ) ;     //日時の取得

//テーブルにデータを入力
if (!empty($name) && !empty($comment ) ) {   
	$sql = $pdo -> prepare("INSERT INTO management (name , comment ,date) VALUES (:name, :comment, :date )");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
	$sql -> execute();
}

//投稿できたか否かのコメントを表示
if (empty($name) || empty($comment ) ) {           
	$result1 = "名前とコメントを入力して下さい" ;
} else {
	$result1 = "書き込みました！" ; 
}
echo $result1 ; 

$sql = "SELECT * FROM management ORDER BY id ASC";                     //実行するqueryの作成
$res = $pdo->query($sql);                  //クエリを実行
$datas = $res->fetchAll();                    //取得したデータを全てフェッチする

//配列なのでカウントは０から
$count = count($datas);        //ログの数
$max = 5;                            //1ページあたりの表示数
$limit = ceil($count/$max);  //最大ページ数
 
$page = empty($_GET["page"])? 1:$_GET["page"];//ページ番号

//ページングを表す関数
function paging($limit, $page, $disp=5){
    //$dispはページ番号の表示数
    $next = $page+1;
    $prev = $page-1;
     
    //ページ番号リンク用
    $start =  ($page-floor($disp/2) > 0) ? ($page-floor($disp/2)) : 1;    //始点
    $end =  ($start > 2) ? ($page+floor($disp/2)) : $disp;                     //終点　　//1から始まる時は5まで
    $start = ($limit < $end)? $start-($end-$limit):$start;                        //始点再計算
     
    if($page != 1 ) {
         print '<a href="?page='.$prev.'">&laquo; back</a>';     //&laquoは<<のこと
    }
     
    //最初のページへのリンク
    if($start >= floor($disp/2)){
        print '<a href="?page=1">1</a>';
        if($start > floor($disp/2)) {
        	 print "...";                             //ドットの表示
        }
    }
     
     
    for($i=$start; $i <= $end ; $i++){//ページリンク表示ループ
         
        $class = ($page == $i) ? ' class="current" ':"";//現在地を表すCSSクラス
         
        if($i <= $limit && $i > 0 )//1以上最大ページ数以下の場合
            print '<a href="?page='.$i.'"'.$class.'>'.$i.'</a>';//ページ番号リンク表示
         
    }
     
    //最後のページへのリンク
    if($limit > $end){
        if($limit-1 > $end ) {
            print "...";                    //ドットの表示
            print '<a href="?page='.$limit.'">'.$limit.'</a>';
        }
    }
         
    if($page < $limit){
        print '<a href="?page='.$next.'">next &raquo;</a>';    //next>>を表示
    }
     
}
 
 //データを表示する関数
function disp_log($page,$max){
     
    global $datas,$count,$date;
     
    $start = ($page == 1)? 0 : ($page-1) * $max;     //ページ内に表示されるはじめの数
    $end   = ($page * $max);                                    //ページ内に表示される最後の数
     
	for($i=$start;$i<$end;$i++){
        if($i >= $count){
            break;
        }
        $data = $datas[$i];
		echo "<hr>";
		echo $data['date']."<br>" ;
		echo "名前:".$data['name']."<br>";
		echo "コメント:".$data['comment']."<br>";
?>
			<form action = Eupho_members.php method = "post">
				<input type="submit" value="削除">
				<input type="hidden" name="id1" value="<?=$data['id']?>">
			</form>
			<form action=Eupho_management_edit.php method="post" >
				<input type="submit" value="編集">
				<input type="hidden" name="id2" value="<?=$data['id']?>">
			</form>
<?php 
	}
}      //関数の終わり

//削除機能
if (!empty($_POST['id1']) ) {
$sql = "SELECT * FROM management";
$results = $pdo -> query($sql) -> fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE); 
$results = $results[$_POST['id1']] ;
$sql = "DELETE FROM management WHERE id={$_POST['id1']}";       //削除
$pdo -> query($sql);    //実行
echo "<br>投稿を削除しました<br>" ;
}

//編集機能
if (!empty($_POST['id3']) ) {
	$sql = "SELECT * FROM management";
	$results = $pdo -> query($sql) -> fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE); 
	$results = $results[$_POST['id3']] ;
	$Name = $_POST['Name'];
	$Comment = $_POST['Comment'];
	$sql = "UPDATE management set name = '$Name' , comment = '$Comment' WHERE id = {$_POST['id3']}";  //編集
	$pdo -> query($sql);     //実行
	echo "<br>投稿を編集しました<br>" ;
} 

//データを表示
echo "<br>";
paging($limit,$page);
disp_log($page,$max);

?>
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
