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

	$pdo = new PDO($dsn, $username, $passwd, $options);

	$query  = "INERT INTO test_id( testId, userName, useFlag, managerId, remark, registDate )";
	$query .= "VALUES( :testId, :userName, :useFlag, :managerId, :remark, NOW() ) ";

	$stmt = $pdo->prepare($query);
	$stmt->bindValue( ':testId', 'billingtest', PDO::PARAM_STR );
	$stmt->bindValue( ':userName', 'ygkim', PDO::PARAM_STR );
	$stmt->bindValue( ':useFlag', TRUE, PDO::PARAM_BOOL );
	$stmt->bindValue( ':managerId', 'ykkim02', PDO::PARAM_STR );
	$stmt->bindValue( ':remark', 'insert testId', PDO::PARAM_STR );

	$returnCode = $stmt->execute();

	if( $returnCode == false ){
		$errMg = 'fail to insert';
		throw new ErrorException( $errMsg );
	}



} catch (Exception $e) {

	echo '<pre>'.print_r( $e, true ).'</pre>';

}
?>