<?php 

require_once('blog2.php');


$id = $_GET['id'];

$blog = new Blog;
$blog->blogDelete($id);

$_SESSION=array();
session_destroy();

?>

<p>ブログを削除しました！</p>
<p><a href="../public/index2.php">戻る</a></p>