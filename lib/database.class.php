<?php

class Database{

	protected $dbh = null;

	public function __construct( $dsn, $username, $passwd, $options=null ){

		$dsn = 'mysql:host='.$dsn;

		if( $options == null ){
			$options = array(
				PDO::ATTR_PERSISTENT 	=> true,
				PDO::ATTR_ERRMODE 		=> PDO::ERRMODE_EXCEPTION
			);
		}

		try{
			$this->dbh = new PDO($dsn, $username, $passwd, $options);
		}catch(PDOException $e){
			$this->dbh = null;
			die( $e->getMessage() );
		}
	}

	public function getDbh(){
		return $this->dbh;
	}



}