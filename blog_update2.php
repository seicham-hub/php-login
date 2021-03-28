<?php

session_start();

require_once('blog2.php');

$blog = new Blog;


if($_SERVER['REQUEST_METHOD'] === 'POST'){

	
	$post = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);


	if($_SESSION['token'] === $post['token'] && isset($_SESSION['token'])){


		// バリデーション
		$_SESSION['id'] = $post['id'];
		$err = $blog->validate($post,'update_form2.php');


		// セッションを破棄する
		$_SESSION = array();
		session_destroy();

		// ブログを更新
		$blog->blogUpdate($post);
	}

	
}else{
	exit('不正なリクエストです');
} 

?>

<p>ブログを更新しました！</p>
<p><a href="../public/index2.php">戻る</a></p>

