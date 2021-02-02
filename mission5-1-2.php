<!DOCTYPE html> 
<html lang="ja"> 
<html> 
    <meta charset="utf-8"> 
    <title>mission5-1</title> 
    <h1>掲示板</h1> 
</html> 
<body> 
<?php 
//初期値宣言 
$dt = date("Y-m-d H:i:s"); 
$editNumber = ''; 
$editName = ''; 
$editComment = ''; 
$editPassword = ''; 
//テーブル作成 
$sql = "CREATE TABLE IF NOT EXISTS mission5_1" 
." (" 
."id INT AUTO_INCREMENT PRIMARY KEY," 
."name char(32)," 
."comment TEXT," 
."date datetime," 
."pass char(32)" 
.");"; 
$stmt = $pdo->query($sql); 

if(isset($_POST["edit"])){ 
    if(!empty($_POST["edit_num"])){ 
        if(!empty($_POST["edit_pass"])){ 
            $id = $_POST["edit_num"]; 
            $pass = $_POST["edit_pass"]; 
            $sql = 'SELECT * FROM mission5_1 WHERE id=:id AND pass=:pass';
            $stmt = $pdo->prepare($sql); 
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR); 
            $stmt->execute(); 
            $results = $stmt->fetchAll(); 
            foreach($results as $result){ 
                $editNumber = $result['id']; 
                $editName = $result['name']; 
                $editComment = $result['comment']; 
                $editPassword = $result['pass']; 
            } 
        } 
        else{ 
            echo "パスワードを入力してください.<br>"; 
        } 
    } 
    else{ 
        echo "編集する番号をしてください.<br>"; 
    } 
} 

if(isset($_POST["submit"])){ 
    if(empty($_POST["name"])){ 
        echo "名前を入力してください.<br>"; 
    } 
    else if(empty($_POST["comment"])){ 
        echo "コメントを入力してください.<br>"; 
    } 
    else if(empty($_POST["pass"])){ 
        echo "パスワードを入力してください.<br>"; 
    } 
    else{ 
        if(empty($_POST["num"])){ 
            $sql = $pdo -> prepare("INSERT INTO mission5_1 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR); 
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR); 
            $sql -> bindParam(':date', $date, PDO::PARAM_STR); 
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR); 
            $name = $_POST["name"]; 
            $comment = $_POST["comment"]; 
            $date = $dt; 
            $pass = $_POST["pass"]; 
            $sql -> execute(); 
        } 
        else{ 
            $id = $_POST["num"]; 
            $name = $_POST["name"]; 
            $comment = $_POST["comment"]; 
            $pass = $_POST["pass"]; 
            $sql = 'UPDATE mission5_1 SET name=:name,comment=:comment,pass=:pass WHERE id=:id';
            $stmt = $pdo->prepare($sql); 
            $stmt->bindParam(':name', $name, PDO::PARAM_STR); 
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR); 
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR); 
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
            $stmt->execute(); 
        } 
    } 
} 

else if(isset($_POST["delete"])){ 
    if(empty($_POST["del_num"])){ 
        echo "番号を指定してください.<br>"; 
    } 
    else if(empty($_POST["del_pass"])){ 
        echo "パスワードを入力してください.<br>"; 
    } 
    else{ 
        $del_id = $_POST["del_num"]; 
        $del_pass = $_POST["del_pass"]; 
        $sql = 'delete from mission5_1 WHERE id=:id AND pass=:pass'; 
        $stmt = $pdo->prepare($sql); 
        $stmt->bindParam(':id', $del_id, PDO::PARAM_INT); 
        $stmt->bindParam(':pass', $del_pass, PDO::PARAM_STR); 
        $stmt->execute(); 
    } 
} 

else if(isset($_POST["del_all"])){ 
    $all_delete = $_POST["all_delete"]; 
    if($all_del_pass == $all_delete){ 
        $sql = 'TRUNCATE TABLE mission5_1'; 
        $stmt = $pdo->query($sql); 
    } 
    else{ 
        echo "パスワードが間違っています"; 
    } 
} 
?> 

<h2>新規投稿</h2> 
<form action="" method="post"> 
    <input type="hidden" name="num" value="<?php echo $editNumber; ?>"> 
    <input type="text" name="name" placeholder="名前を入力" value="<?php echo $editName; ?>"><br> 
    <input type="text" name="comment" placeholder="コメントを入力" value="<?php echo $editComment; ?>"><br> 
    <input type="password" name="pass" placeholder="パスワードを入力" value="<?php echo $editPassword; ?>"> 
    <input type="submit" name="submit"><br> 
</form> 
<h2>削除</h2> 
<form action="" method="POST"> 
    <input type="number" name="del_num" placeholder="削除する番号を入力"><br>
    <input type="password" name="del_pass" placeholder="パスワードを入力"> 
    <input type="submit" name="delete" value="削除"> 
</form> 
<h2>編集</h2> 
<form action="" method="POST"> 
    <input type="number" name="edit_num" placeholder="編集する番号を入力"><br> 
    <input type="password" name="edit_pass" placeholder="パスワードを入力"> 
    <input type="submit" name="edit" value="編集"> 
</form> 
<hr> 

<?php 
$show_sql = "SELECT * FROM mission5_1"; 
$show_stmt = $pdo->query($show_sql); 
$results = $show_stmt->fetchAll(); 
foreach($results as $result){ 
    echo $result['id'].'<br>'; 
    echo "投稿者:" . $result['name'].'<br>'; 
    echo "投稿日時:" . $result['date'].'<br>'; 
    echo $result['comment'].'<br>'; 
    echo "<hr>"; 
} 
?> 
<h2>テーブル削除(管理者)</h2> 
<form action="" method="POST"> 
    <input type="password" name="all_delete" placeholder="パスワードを入力"> 
    <input type="submit" name="del_all" value="削除"> 
</form> 
</body> 
</html>