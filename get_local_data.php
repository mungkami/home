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

	$query  = "SELECT payTableKey, serviceName ";
	$query .= "FROM pay_data ";
	$query .= "";

	$stmt = $dbh->prepare($query);
	$stmt->execute();
	$list = $stmt->fetchAll(PDO::FETCH_ASSOC);

	foreach($list as $row => $data ){
		$serviceName = iconv( 'utf-8', 'euc-kr', $data['serviceName'] );
		echo $serviceName.PHP_EOL;
	}
	exit;


} catch ( Exception $e ) {
	echo '<pre>' . print_r ( $e, true ) . '</pre>';
}

?>