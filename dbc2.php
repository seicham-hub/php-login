<?php 

require_once(__DIR__.'/../config/env2.php');

class Dbc{

	protected $table_name;

	/**
	 * データベース接続
	 * @param void
	 * @return instance $dbh
	 */
	protected function dbconnect(){

		$host = DB_HOST;
		$dbname = DB_NAME;
		$user = DB_USER;
		$pass = DB_PASS;

		$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";


		try{
			$dbh = new PDO($dsn,$user,$pass,[
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_EMULATE_PREPARES => false,
			]);

		}catch(PDOException $e){
			echo '接続に失敗しました'.$e->getMessage();
			exit();
		}
		
		return $dbh;

	}

	/**
	 * データをすべて取得する
	 * @param void
	 * @return array $result
	 */
	public function getAll(){
	$dbh = self::dbconnect();

	$sql = "SELECT* FROM $this->table_name";
	$stmt = $dbh->query($sql);
	$result = $stmt->fetchall(PDO::FETCH_ASSOC);

	return $result;
	}


	/**
	 * idから一行を取得する
	 * @param str $id
	 * @return array $result
	 */
	public function getById($id){

		if(empty($id)){
		exit('不正なIDです');
		}

		try{
			$dbh = self::dbconnect();
			$sql = "SELECT* FROM $this->table_name WHERE id = :id";
			$stmt = $dbh->prepare($sql);
			$stmt->bindValue(':id',(int)$id,PDO::PARAM_INT);
			$stmt->execute();

			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
		}catch(\Exception $e){
			echo '取得に失敗しました'.$e;
		}
		if($result === false){
			exit('ブログがありません');
		}
		
		return $result;
	} 





}

?>