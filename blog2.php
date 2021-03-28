<?php 


require_once('dbc2.php');


Class Blog extends Dbc{

	protected $table_name = 'blogs';
	

	/**
	 * @param int 
	 * @return str カテゴリーの文字列
	 */
	public function setCategoryName($category){

		if(isset($category)){
			if($category === 1){
				return 'ブログ';
			}else if($category === 2){
				return '雑記';
			}else{
				return 'その他';
			}
		}else{
			return NULL;
		}
		

	}


	/**
	 * ブログを作る
	 * @param array $post 
	 * @return 
	 */
	public function blogCreate($post){

		$dbh = $this->dbconnect();
		$dbh->beginTransaction();

		try{
			
			$sql = "INSERT INTO blogs(title,content,category,publish_status) VALUES(:title,:content,:category,:publish_status)";
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(':title',$post['title'],PDO::PARAM_STR);
			$stmt->bindValue(':content',$post['content'],PDO::PARAM_STR);
			$stmt->bindValue(':category',$post['category'],PDO::PARAM_INT);
			$stmt->bindValue(':publish_status',$post['publish_status'],PDO::PARAM_INT);
			$stmt->execute();
			$dbh->commit();

		}catch(\Exception $e){
			$dbh->rollBack();
			echo 'ブログ投稿に失敗しました'.$e;
			exit();
		}

	}
	/**
	 * ブログを更新する
	 * @param array $post 
	 * @return 
	 */
	public function blogUpdate($post){
		$dbh = $this->dbconnect();
		$dbh->beginTransaction();

		$sql = "UPDATE blogs SET 
		title = :title, content = :content, category = :category, publish_status = :publish_status
		WHERE id = :id";

		try{
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(':title',$post['title'],PDO::PARAM_STR);
			$stmt->bindValue(':content',$post['content'],PDO::PARAM_STR);
			$stmt->bindValue(':category',$post['category'],PDO::PARAM_INT);
			$stmt->bindValue(':publish_status',$post['publish_status'],PDO::PARAM_INT);
			$stmt->bindValue(':id',$post['id'],PDO::PARAM_INT);
			$stmt->execute();
			$dbh->commit();
		}catch(\Exception $e){
			$dbh->rollBack();
			echo '更新に失敗しました'.$e;
			exit();
		}

	}

	/**
	 * ブログを削除する
	 * @param $id
	 */
	public function blogDelete($id){

		try{
			$dbh = $this->dbconnect();
			$dbh->beginTransaction();
			$sql = "DELETE FROM $this->table_name WHERE id = :id";
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(':id',$id,PDO::PARAM_INT);
			$stmt->execute();
			$dbh->commit();

			

		}catch(\Exception $e){
			$dbh->rollback();
			echo 'ブログ削除に失敗しました'.$e;
			exit();
		}

	}


	/**
	 * バリデーション
	 * @param array $post
	 * @param string $url
	 * @return array $err
	 */
	public function validate($post,$url){
		
		$categoryName = self::setCategoryName((int)$post['category']);


		$err = [];

		if(empty($post['title']) || mb_strlen($post['title'])>191){
			$err['title'] = 'タイトルを191文字以内で入力してください。';
		}
		if(empty($post['content'])){
			$err['content'] = '本文を入力してください。';
		}
		if(empty($categoryName)){
			$err['category'] = 'カテゴリーを選択してください。';
		}
		if(empty($post['publish_status'])){
			$err['publish_status'] = '公開ステータスを選択してください。';
		}

		if(count($err) > 0){
			$_SESSION['err'] = $err;
			var_dump($_SESSION);
			header('Location:../public/'.$url);
			exit();
		}


	}

	/**
	 * エスケープ処理
	 * @param str $str
	 * @return str 処理後の文字列
	 */
	static function h ($str){
	    return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
	}

}

?>