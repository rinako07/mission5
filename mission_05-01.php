<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
<div style="background-color: rosybrown">
<strong> <span style="font-size: 30px" ; >簡易掲示板</span></strong>
</div>
<body style="background-color:seashell;"><br>

<?php
  $date = date("Y年m月d日 H時i分s秒");
  
    $dsn = 'mysql:dbname=データベース名;host=localhost';
    $user = 'ユーザー名';
    $pass = "パスワード";
    $pdo = new PDO($dsn,$user,$pass,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
    
    $sql = "CREATE TABLE IF NOT EXISTS mission5"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date TEXT,"
    . "password TEXT"
    .");";
    $stmt = $pdo->query($sql);
  
if( !empty($_POST["name"] ) && !empty ($_POST["comment"]) && !empty ($_POST["password"])){
       
    $name = $_POST["name"];
    $comment = $_POST["comment"]; 
    $password = $_POST["password"];
     
    if (empty ($_POST['hiddenNO'])) { 
        
        $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':password', $password, PDO::PARAM_STR);
       
        $sql -> execute();
         
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
        echo $row['id'].' ' . $row['name'].' '. $row['comment'].' '. $row['date'].'<br>';
        echo "<hr>";
        }
    
    } else {
        
    $editname = $_POST["name"];
    $editcomment = $_POST["comment"]; 
    $hiddenNO = $_POST['hiddenNO'];

    $sql = 'SELECT * FROM mission5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        
        if (($hiddenNO == $row['id']) && ($password == $row['password'])){  
            
        $id = $hiddenNO;
        $name = $editname;
        $comment = $editcomment;
        $sql = 'UPDATE mission5 SET name=:name,comment=:comment WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        echo ">> $id 編集しました。<br>";
        echo "<hr>";
        
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
            foreach ($results as $row){
            echo $row['id'].' ' . $row['name'].' '. $row['comment'].' '. $row['date'].'<br>';
            echo "<hr>";
            }
        }
    } 
    }
}
 
elseif (!empty ($_POST ["deleteNO"] ) && !empty ($_POST["password2"])){
    $deleteNO = $_POST ["deleteNO"];
    $pass2 = $_POST["password2"];
    
    $sql = 'SELECT * FROM mission5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        if (($deleteNO == $row['id']) && ($pass2 == $row['password'])){           
   
        $id = $deleteNO;             
        $sql = 'delete from mission5 where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo ">> $id 削除しました。<br>";
        echo "<hr>";
            
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
            foreach ($results as $row){
            echo $row['id'].' ' . $row['name'].' '. $row['comment'].' '. $row['date'].'<br>';
            echo "<hr>";
            }
        }
    }
 }

if (!empty ( $_POST["editNO"] ) && !empty ($_POST["password3"])){
    $editNO = $_POST['editNO'];
    $pass3 = $_POST["password3"];
    
    $sql = 'SELECT * FROM mission5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        if (($editNO == $row['id']) && ($pass3 == $row['password'])){   
   
           $name =  $row['name'] ;
           $comment = $row['comment'] ;
        }
    }   
}

        

?>
 <form method='POST' action=''><br>
 <strong> <span style="font-size: 20px" ; >▼ 投稿フォーム</span></strong><br>
    お名前　　：<input type='text' name='name' value ='<?php if (!empty ( $_POST["editNO"] ) && !empty ($_POST["password3"])){
                                                        if (($editNO == $row['id']) && ($pass3 == $row['password'])){   
                                                       echo $name;
                                                      } }?>'><br>
    コメント　：<input type='text' name='comment' value ='<?php if (!empty ( $_POST["editNO"] ) && !empty ($_POST["password3"])){
                                                        if (($editNO == $row['id']) && ($pass3 == $row['password'])){   
                                                       echo $comment;
                                                      } }?>'><br>
    パスワード：<input type='text' name='password'>
    <input type='hidden' name='hiddenNO' value ='<?php if (!empty ( $_POST["editNO"] ) && !empty ($_POST["password3"])){
                                                       if (($editNO == $row['id']) && ($pass3 == $row['password'])){   
                                                       echo $editNO;
                                                      } }?>'>
    <input type='submit' name='submit' value='送信'><br>
</form>
 
 <br>
<form method='POST' action=''> 
 <strong> <span style="font-size: 20px" ; >▼ 削除フォーム</span></strong><br>
    削除番号　：<input type='number' name='deleteNO'><br>
    パスワード：<input type='text' name='password2'>
    <input type='submit' name='submit' value='削除'><br>
 </form>
 <br>
 
 <form method='POST' action=''>
 <strong> <span style="font-size: 20px" ; >▼ 編集フォーム</span></strong><br>
    編集番号　：<input type='number' name='editNO'value ='<?php if (!empty ( $_POST["editNO"] ) && !empty ($_POST["password3"])){
                                                        if (($editNO == $row['id']) && ($pass3 == $row['password'])){   
                                                    echo $editnumber;
                                                       }}?>'><br> 
    パスワード：<input type='text' name='password3'>
     <input type='submit' name='submit' value='編集'><br>
 </form>

</body>
</html>
      
      