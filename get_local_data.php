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

	$query  = "SELECT * ";
	$query .= "FROM pay_data ";
	$query .= "ORDER BY idx DESC LIMIT 2";

	$stmt = $dbh->prepare($query);
	$list = $stmt->fetchAll();

	var_dump( $list );
	exit;


} catch ( Exception $e ) {
	echo '<pre>' . print_r ( $e, true ) . '</pre>';
}

?>