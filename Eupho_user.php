<?php
//データベースへの接続
$dsn = "データベース名";
$user = "ユーザー名";
$password = "パスワード";
$pdo = new PDO($dsn , $user , $password);

//メンバーテーブル作成
$sql = "CREATE TABLE member (id INT auto_increment not null primary key,"
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
<?php
$date = date( "Y / m / d ") ;     //日時の取得
$name = $_POST["name"] ;  
$nickname = $_POST["nickname"] ;  
$email = $_POST["email"] ;  
$hometown = $_POST["hometown"] ;  
$university = $_POST["university"] ;  
$password1 = $_POST["password1"] ;  
$password2 = $_POST["password2"] ;  

//テーブルにデータを入力
if ($password1 == $password2) {
	$sql = $pdo -> prepare("INSERT INTO member (date, name, nickname, email, hometown, university, password1, password2 ) VALUES (:date, :name, :nickname, :email, :hometown, :university, :password1, :password2) ");
	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':nickname', $nickname, PDO::PARAM_STR);
	$sql -> bindParam(':email', $email, PDO::PARAM_STR);
	$sql -> bindParam(':hometown', $hometown, PDO::PARAM_STR);
	$sql -> bindParam(':university', $university, PDO::PARAM_STR);
	$sql -> bindParam(':password1', $password1, PDO::PARAM_STR);
	$sql -> bindParam(':password2', $password2, PDO::PARAM_STR);
	$sql -> execute();
}

$sql = "SELECT * FROM member ORDER BY id ASC";                     //実行するqueryの作成
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
		echo $data['date'];
		echo "名前:".$data['name'];
		echo "ニックネーム:".$data['nickname'];
		echo "出身:".$data['hometown'];
		echo "大学:".$data['university'];
	}
}      //関数の終わり

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
			<p><a href="Eupho_user.php">メンバー表</a></p>
			<p><a href="Eupho_pic.php">写真</a></p>
			<p><a href="Eupho_movie.php">動画</a></p>
		</div>
	</footer>
</body>
</html>