<title>ニュー掲示板</title>
<p>
<h1><ニュー掲示板></h1>
</p>

<form action="<?php echo($_SERVER['PHP_SELF']) ?>" method="post">
  <table>
    <tr><td>名前：</td>
    <td><input type="text" name="name"></td></tr>
    <tr><td>コメント：</td>
    <td><textarea name="comment" cols="30" rows="5"></textarea></td></tr>
    <tr><td>パスワード:</td>
    <td><input type ="text" name="pass"></td></tr>
    <tr><td><input type="submit" name="submit" value="送信"></td></tr></table>
</form>

<?php
// 新規追加 (idがない場合)
  if(isset($_POST["submit"]) && !isset($_POST["id"])) {
  $fp1 = fopen('kadai_2_6.dat','r+');
  $num = fgets($fp1);
  if (empty($num)){$num =1;}
  fseek($fp1,0);
  fputs($fp1,$num + 1);
  fclose($fp1);
  //フォーム内容(名前、コメント、パスワード)と送信時間、送信番号をファイルに書き込む。
  $name = $_POST["name"];
  $comment = $_POST["comment"];
  $pass =$_POST["pass"];
  $file = "kadai_2_6.txt";  //.txtはフォーム内容
  $fp2 = fopen($file, "a+");
  $timestamp = date("Y/m/d H時i分s秒");
  fwrite($fp2, "$num|$name|$comment|$timestamp|$pass\n");
  fclose($fp2);
}

// 変更 (idがある場合)
if(isset($_POST["submit"]) && isset($_POST["id"])) {
  $contents = file("kadai_2_6.txt");
  $fp1 = fopen('kadai_2_6.txt','w');
  $edit_num =  $_POST["id"];
  foreach($contents as $content) {
    $parts = explode("|", $content);
    if($parts[0] == $edit_num){
      $name = $_POST["name"];
      $comment = $_POST["comment"];
      $timestamp = date("Y/m/d H時i分s秒");
      $pass  =$_POST["pass"];
      fwrite($fp1, "$edit_num|$name|$comment|$timestamp|$pass\n");
    } else {
      fwrite($fp1, "$content");
    }
  }
  fclose($fp1);
}


?>
<p>-----------------------</p>
<p>削除したい番号とそのパスワードを、半角数字で入力してください(両方入力しないと反応しません)</p>
<form action="<?php echo($_SERVER['PHP_SELF']) ?>" method="post">
	<table>
	<tr><td>削除対象番号:</td>
	<td><input type="text" name="del_num"></td></tr>
	<tr><td>パスワード:</td>
	<td><input type ="text" name="delpass"></td></tr>
<p>
<tr><td><input type="submit" name="del_btn" value="削除"></td></tr>
<tr><td><input type="hidden"  name="del" value="sakuzyo"></td></tr></table>
</p>
</form>

<?php
			//&& isset($_POST["del_num"]) && isset($_POST["delpass"])){  //すべて入力されたら
  if(isset($_POST["del_btn"])){
  $contents = file("./kadai_2_6.txt");
  $fp3 = fopen("./kadai_2_6.txt",'w+');
	$del_num =  $_POST["del_num"];
        $delpass  =  $_POST["delpass"];
 	foreach ($contents as $content){
        $parts = explode("|", trim($content));
 	if($parts[0] == $del_num && $parts[4] == $delpass){					//(($parts[0] == $del_num) and 
			fwrite($fp3,"消去しました。\n");
			echo "成功";
//if(($parts[0]!=$del_num) or ($parts[4]!=$delpass)){
			} else {
                        fwrite($fp3,"$content");
			echo"失敗";
			       }
				}//ループ
fclose($fp3);
  					}//全て入力されたら


// 表示
$contents = file("kadai_2_6.txt");
foreach ($contents as $content) {
  $parts = explode("|", $content);
  foreach ($parts as $part) {
    echo "<table><tr>$part</tr></table>";
  }
}
?>
<p>-----------------------</p>
<p>編集したい番号とそのパスワードを、半角数字で入力してください(両方入力しないと反応しません)</p>
<form method="post" action="<?php echo($_SERVER['PHP_SELF']) ?>">
<input type="text" name="edit_num" placeholder="<編集対象番号>例)1" value="<?= isset($_POST['edit_num']) ? $_POST['edit_num'] : null ?>">
<input type ="text" name="passs"  placeholder="<パスワード>" value="<?= isset($_POST['passs']) ? $_POST['passs'] : null ?>">
　<input type="submit"  name="edit_btn" value="編集する">
  <input type="hidden"  name="edit" value="hensyu">
</form>

<?php
//パスワードの検証
  if(isset($_POST["edit_btn"])){
//&& isset($_POST["edit_num"]) && isset($_POST["passs"])){  //すべて入力されたら
   $edit_num =  $_POST["edit_num"];
   $passs    =  $_POST["passs"];
 	foreach ($contents as $content){
        $parts = explode("|", trim($content));
 	if($parts[0] == $edit_num){
		if($parts[4] == $passs){
//and ($parts[4] == $passs)){//
?>
<form action="<?php echo($_SERVER['PHP_SELF']) ?>" method="post">
  <input type="hidden" name="id" value="<?= $parts[0] ?>">
  <table>
    tr><td>名前：</td>
    <td><input type="text" name="name" value="<?= $parts[1] ?>"></td></tr>
    <tr><td>コメント：</td>
    <td><textarea name="comment" cols="30" rows="5"><?= $parts[2] ?></textarea></td></tr>
    <tr><td>パスワード:</td>
    <td><input type ="text" name="pass"value="<?= $parts[4] ?>"></td></tr>
    <tr><td><input type="submit" name="submit" value="送信"></td></tr>
  </table>
</form>
<?php
		
	}else{
			      echo "パスワードが一致しません";
			     }
			}else{
                              echo "編集番号が一致しません";//パス編集番号一致せず
			     }
				}//ループ
  					}//全て入力されたら

?>