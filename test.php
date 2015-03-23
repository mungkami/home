<?php
#============================
# PDO <-> local DB testing..
# @auther : ykkim02
# date : 2015-03-20
#============================
try {

	echo 'test git';

	$dsn = 'mysql:host=localhost;dbname=test';
	$username = 'root';
	$passwd = 'yg3035';
	$options = null;

	$dbh = new PDO($dsn, $username, $passwd, $options);
	$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

	$query  = "INSERT INTO test_id( testId, userName, useFlag, managerId, remark, registDate )";
	$query .= "VALUES( :testId, :userName, :useFlag, :managerId, :remark, NOW() ) ";

	$stmt = $dbh->prepare($query);

	if( !$stmt ){
		print_r( $dbh->errorInfo() );
	}

	$testId = 'billingtest';
	$userName = 'ygkim';
	$useFlag = TRUE;
	$managerId = 'ykkim02';
	$remark = 'insert test';


	$stmt->bindParam( ':testId', $testId, PDO::PARAM_STR );
	$stmt->bindParam( ':userName', $userName, PDO::PARAM_STR );
	$stmt->bindParam( ':useFlag', $useFlag, PDO::PARAM_BOOL );
	$stmt->bindParam( ':managerId', $managerId, PDO::PARAM_STR );
	$stmt->bindParam( ':remark', $remark, PDO::PARAM_STR );

	$returnCode = $stmt->execute();

	if( $returnCode == false ){
		print_r( $dbh->errorInfo() );
	}

} catch (Exception $e) {

	echo '<pre>'.print_r( $e, true ).'</pre>';

}
?>