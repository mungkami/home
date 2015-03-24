<?php

#local test getData API

try {
	echo 'test API call';

	include_once 'lib/net.class.php';

	$getDataurl  = 'https://billingadmin.cafe24.com/test/ykkim02/test_get_pay_data.php';

	$sslFlag = Net::getSSLFlag( $getDataurl );

	$getData = Net::getHtml( $getDataurl, 'GET', 30, TRUE );
	$getData = unserialize($getData);

	#set Database
	include_once 'lib/database.class.php';

	$dsn = 'localhost;dbname=payment';
	$username = 'root';
	$passwd = 'yg3035';	//test

	$database = new Database($dsn, $username, $passwd);
	$dbh = $database->getDbh();

	$query  = "INSERT INTO pay_data ( payTableKey, payMethod, payAmount, ";
	$query .= "payStatus, serviceName, productCode, parentId, userId, orderName, orderNo, ";
	$query .= "pgId, tradeNo, approvalNo, bankName, accountNo, accountName, escrowYN, escrowNo, ";
	$query .= "mobileNo, regDate, bankCheckIdx, updateDate, testFlag, refundIdx, cancelKey, ";
	$query .= "managerId, devRemark, remark) VALUES( ";
	$query .= ":payTableKey, :payMethod, :payAmount, ";
	$query .= ":payStatus, :serviceName, :productCode, :parentId, :userId, :orderName, :orderNo, ";
	$query .= ":pgId, :tradeNo, :approvalNo, :bankName, :accountNo, :accountName, :escrowYN, :escrowNo, ";
	$query .= ":mobileNo, :regDate, :bankCheckIdx, :updateDate, :testFlag, :refundIdx, :cancelKey, ";
	$query .= ":managerId, :devRemark, :remark )";

	$stmt = $dbh->prepare($query);
	$stmt->bindParam( ':payTableKey', $payTableKey, PDO::PARAM_STR );
	$stmt->bindParam( ':payMethod', $payMethod, PDO::PARAM_STR );
	$stmt->bindParam( ':payAmount', $payAmount, PDO::PARAM_INT );
	$stmt->bindParam( ':payStatus', $payStatus, PDO::PARAM_STR );
	$stmt->bindParam( ':serviceName', $serviceName, PDO::PARAM_STR );
	$stmt->bindParam( ':productCode', $productCode, PDO::PARAM_STR );
	$stmt->bindParam( ':parentId', $parentId, PDO::PARAM_STR );
	$stmt->bindParam( ':userId', $userId, PDO::PARAM_STR );
	$stmt->bindParam( ':orderName', $orderName, PDO::PARAM_STR );
	$stmt->bindParam( ':orderNo', $orderNo, PDO::PARAM_STR );
	$stmt->bindParam( ':pgId', $pgId, PDO::PARAM_STR );
	$stmt->bindParam( ':tradeNo', $tradeNo, PDO::PARAM_STR );
	$stmt->bindParam( ':approvalNo', $approvalNo, PDO::PARAM_STR );
	$stmt->bindParam( ':bankName', $bankName, PDO::PARAM_STR );
	$stmt->bindParam( ':accountNo', $accountNo, PDO::PARAM_STR );
	$stmt->bindParam( ':accountName', $accountName, PDO::PARAM_STR );
	$stmt->bindParam( ':escrowYN', $escrowYN, PDO::PARAM_STR );
	$stmt->bindParam( ':escrowNo', $escrowNo, PDO::PARAM_STR );
	$stmt->bindParam( ':mobileNo', $mobileNo, PDO::PARAM_STR );
	$stmt->bindParam( ':regDate', $regDate, PDO::PARAM_STR );
	$stmt->bindParam( ':bankCheckIdx', $bankCheckIdx, PDO::PARAM_INT );
	$stmt->bindParam( ':updateDate', $updateDate, PDO::PARAM_STR );
	$stmt->bindParam( ':testFlag', $testFlag, PDO::PARAM_STR );
	$stmt->bindParam( ':refundIdx', $refundIdx, PDO::PARAM_INT );
	$stmt->bindParam( ':cancelKey', $cancelKey, PDO::PARAM_STR );
	$stmt->bindParam( ':managerId', $managerId, PDO::PARAM_STR );
	$stmt->bindParam( ':devRemark', $devRemark, PDO::PARAM_STR );
	$stmt->bindParam( ':remark', $remark, PDO::PARAM_STR );

	#getData -> db INSERT
	foreach( $getData as $key => $row ){

		$payTableKey 	= $key;
		$payMethod 		= $row['payMethod'];
		$payAmount		= $row['payAmount'];
		$payStatus		= $row['payStatus'];
		echo 'serviceName : '.$serviceName.PHP_EOL;
		$serviceName = iconv( 'utf-8', 'euc-kr', $row['serviceName'] );
		echo 'serviceName : '.$serviceName.PHP_EOL;
		$serviceName	= $serviceName;
		exit;
		$productCode	= $row['productCode'];
		$parentId		= $row['parentId'];
		$userId			= $row['userId'];
		$orderName		= iconv( 'utf-8', 'euc-kr', $row['orderName'] );
		$orderNo		= $row['orderNo'];
		$pgId			= $row['pgId'];
		$tradeNo		= $row['tradeNo'];
		$approvalNo		= $row['approvalNo'];
		$bankName		= iconv( 'utf-8', 'euc-kr', $row['bankName'] );
		$accountNo		= $row['accountNo'];
		$accountName	= $row['accountName'];
		$escrowYN		= $row['escrowYN'];
		$escrowNo		= $row['escrowNo'];
		$mobileNo		= $row['mobileNo'];
		$regDate		= $row['regDate'];
		$bankCheckIdx	= $row['bankCheckIdx'];
		$updateDate		= $row['updateDate'];
		$testFlag		= $row['testFlag'];
		$refundIdx		= $row['refundIdx'];
		$cancelKey		= $row['cancelKey'];
		$managerId		= $row['managerId'];
		$devRemark		= $row['devRemark'];
		$remark			= $row['remark'];


		//$stmt->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES EUC-KR" );

		$result = $stmt->execute();

		if( $result == false ){
			echo 'Insert Error | payTableKey='.$payTableKey.PHP_EOL;
		}
	}

	unset( $getData, $stmt, $dbh );
	exit;


} catch ( Exception $e ) {
	echo '<pre>' . print_r ( $e, true ) . '</pre>';
}

?>