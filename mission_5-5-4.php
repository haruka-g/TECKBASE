<?php
	//MySQL接続
	$dsn='データベース名';
	$user='ユーザー名';
	$password='パスワード';
	$pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

	//投稿した情報を入れるテーブルの作成
	$sql = "CREATE TABLE IF NOT EXISTS tb11"
	."("
	."id INT AUTO_INCREMENT PRIMARY KEY,"
	."name char(32),"
	."comment TEXT,"
	."date TEXT,"
	."pass TEXT"
	.");";
	$stmt = $pdo->query($sql);
	
	if((empty($_POST["editnum"])) == false){//編集対象番号の中身が空じゃなくfalseだったら
			$editnumber = "";
	}
	
	$editname = "";
	$editcomment = "";


	//投稿フォーム
	if(!empty($_POST["name"]) and !empty($_POST["comment"])){
		//新規投稿
		if (($_POST["edit"] != true)&&empty($_POST["editbuttom"])){//もしhiddenに何もなかったら新規投稿します
			$sql = $pdo->prepare("INSERT INTO tb11(name, comment, date, pass) VALUES(:name, :comment, :date, :pass)");
			$sql->bindParam(':name', $name, PDO::PARAM_STR);
			$sql->bindParam(':comment', $comment, PDO::PARAM_STR);
			$sql->bindParam(':date', $date, PDO::PARAM_STR);
			$sql->bindParam(':pass', $pass, PDO::PARAM_STR);
			$date = date("Y/m/d H:i:s");
			$name = $_POST["name"];
			$comment = $_POST["comment"];
			$pass = $_POST["password"];
			$sql->execute();
		}

		//編集
		if ($_POST["edit"] == true){//もしhiddenに入ってたら
			$id = $_POST["edit"];
			$name = $_POST["name"];
			$comment = $_POST["comment"];
			$date = date("Y/m/d H:i:s");
			$pass = $_POST["password"];
			$sql = 'update tb11 set name = :name, comment = :comment, date = :date, pass = :pass where id = :id';
			$stmt = $pdo -> prepare($sql);
			$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
			$stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt -> bindParam(':date', $date, PDO::PARAM_STR);
			$stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
			$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
			$stmt -> execute();
		}
	}
	
	//削除フォーム
	if(isset($_POST["delete"])){
		$id = $_POST["delete"];
		$sql = 'SELECT * FROM tb11';
		$stmt = $pdo -> query($sql);
		$results = $stmt -> fetchAll();
		foreach($results as $row){
			if($row['id'] == $id){
				if($_POST["deletepassword"] == $row['pass']){
					$sql = 'delete from tb11 where id = :id';
					$stmt = $pdo -> prepare($sql);
					$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
					$stmt -> execute();
		 		}
			}
		}
	}
	
	//編集(変数引継)
	if(isset($_POST["editnum"])){//編集対象番号に中身が入っていたら
		$id = $_POST["editnum"];
		$sql = 'SELECT * FROM tb11';
		$stmt = $pdo -> query($sql);
		$results = $stmt -> fetchAll();
		foreach($results as $row){
			if($row['id'] == $id){
				if($_POST["editpassword"] == $row['pass']){
						$editnumber = $row['id'];
						$editname = $row['name'];
						$editcomment = $row['comment'];
				}
			}
		}
	}

//入力したデータをフォーム下に表示
$dsn='mysql:dbname=tb210066db;host=localhost';
	$user='tb-210066';
	$password='cG5REBnW45';

	$pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql = 'SELECT * FROM tb11';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['date'].',';
		echo $row['pass'].'<br>';
		echo "<hr>";
	}



?>


<form action="" method="POST">

 <input type="hidden" name="edit" value="<?php if(isset($editnumber)){echo $editnumber;}?>">
  <input type="text" name="name" placeholder="名前"  value="<?php if(isset($editname)) {echo $editname;}?>"><br/>
  <input type="text" name="comment"placeholder="コメント" value="<?php if(isset($editcomment)){echo $editcomment;}?>"><br/>
  <input type="text" name="password" placeholder="パスワード" value="<?php if(isset($editpass)){echo $editpass;}?>"><br/>
<input type="submit" value="送信"/><br/><br/>

 <input type="text"name="delete" placeholder="削除対象番号">
 <input type="text" name="deletepassword" placeholder="パスワード" /><br/>
 <input type="submit" value="削除"/><br/><br/>

 <input type="text"name="editnum" placeholder="編集対象番号">
 <input type="text" name="editpassword" placeholder="パスワード" /><br/>
 <input type="submit"  name="editbuttom "value="編集"/>


</form>
