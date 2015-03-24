<?php


#get Data

try {

	#set Database
	include_once 'lib/database.class.php';

	$dsn = 'localhost;dbname=payment';
	$username = 'root';
	$passwd = 'yg3035';	//test

	$database = new Database($dsn, $username, $passwd);
	$dbh = $database->getDbh();

	//$dbh->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES euckr");

	$query  = "SELECT serviceName ";
	$query .= "FROM pay_data ";
	$query .= "ORDER BY idx DESC LIMIT 2";

	$stmt = $dbh->prepare($query);
	$stmt->execute();
	$list = $stmt->fetchAll(PDO::FETCH_COLUMN);

	foreach($list as $data){
		$serviceName = iconv( 'euc-kr', 'utf-8', $data );
		echo $serviceName.PHP_EOL;
	}
	exit;


} catch ( Exception $e ) {
	echo '<pre>' . print_r ( $e, true ) . '</pre>';
}

?>