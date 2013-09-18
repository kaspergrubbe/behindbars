<?php
# Works slightly :/
#->		Version 1.0
class pdoMysql {
	private $cfg;
	private $pdo;
	private $statement;

	function __construct()
	{
		$this->cfg = array(
		'db'      => 'mysql',
		'db_user' => 'root',
		'db_pwd'  => 'response',
		'db_host' => 'localhost',
		'db_db'   => 'bmx_behindbars',
		'db_opts' => array(
			PDO::ERRMODE_SILENT => true,
			PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
		)
		);
		$this->connectDB();
	}
	private function connectDB()
	{
		$this->pdo = new PDO($this->cfg['db'].':host='.$this->cfg['db_host'].';dbname='.$this->cfg['db_db'],$this->cfg['db_user'],$this->cfg['db_pwd'], $this->cfg['db_opts']);
	}
	public function fetch($query, $type = 1)
	{
		// Only for use w/fetchRow, if you dont expect a return use execute_query_NoReturn()
		$stmt = $this->pdo->prepare($query);
		$stmt->execute();
		
		if($type == 1) {
			//return array as both number and associative indices
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			return $stmt->fetchAll();
		} elseif($type == 2) {
			//return array as number indices
			$stmt->setFetchMode(PDO::FETCH_NUM);
			return $stmt->fetchAll();
		} else {
			//return array as associative indices
			$stmt->setFetchMode(PDO::FETCH_BOTH);
			return $stmt->fetchAll();
		}
	}
	public function fetchRow($query, $type = 1)
	{
		// Only for use w/fetchRow, if you dont expect a return use execute_query_NoReturn()
		$stmt = $this->pdo->prepare($query);
		$stmt->execute();
		
		if($type == 1) {
			//return array as both number and associative indices
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			return $stmt->fetch();
		} elseif($type == 2) {
			//return array as number indices
			$stmt->setFetchMode(PDO::FETCH_NUM);
			return $stmt->fetch();
		} else {
			//return array as associative indices
			$stmt->setFetchMode(PDO::FETCH_BOTH);
			return $stmt->fetch();
		}
	}
	public function lastInsert()
	{
		$stmt = $this->pdo->prepare('SELECT LAST_INSERT_ID() AS count');
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result['count'];
	}
	public function insert($query, $arrayValue)
	{
		// $query		= INSERT INTO users (id, username) VALUES (?,?)
		// $arrayValue	= array(NULL, naps)
		$stmt = $this->pdo->prepare($query);
		$stmt->execute($arrayValue);
	}
	public function execute_query_NoReturn($query)
	{
		// Only for stuff WITHOUT return results (This includes SELECT, OPTIMIZE TABLE, etc.)
		// Returns rows affected
		//Return works w/ delete, update
		$count = $this->pdo->exec($query);
		return $count;
	}
	public function closeDB()
	{
		$this->pdo = NULL;
	}
}
?>
