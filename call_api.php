<?php

#local test getData API

try {
	echo 'test API call';

	include_once 'lib/net.class.php';
	/*
	$getDataurl  = 'https://billingadmin.cafe24.com/test/ykkim02/test_get_pay_data.php';

	$sslFlag = Net::getSSLFlag( $getDataurl );

	$getData = Net::getHtml( $getDataurl, 'GET', 30, TRUE );
	$getData = unserialize($getData);

	var_dump( $getData );
	*/

	#set Database
	include_once 'lib/database.class.php';

	$dsn = 'localhost';
	$username = 'root';
	$passwd = 'yg3035';	//test

	$database = new Database($dsn, $username, $passwd);
	$dbh = $database->getDbh();

	$aliveDrivers = $dbh->getAvailableDrivers();
	var_dump( $aliveDrivers );
	exit;

	$dbh->prepare($statement);

} catch ( Exception $e ) {
	echo '<pre>' . print_r ( $e, true ) . '</pre>';
}

?>