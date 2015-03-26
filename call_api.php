<?php

#local test getData API

try {

	echo 'test API call'.PHP_EOL;

	include_once 'lib/net.class.php';

	#set Database
	include_once 'lib/database.class.php';

	$dsn = 'localhost;dbname=payment';
	$username = 'root';
	$passwd = 'yg3035';	//test

	$database = new Database($dsn, $username, $passwd);
	$dbh = $database->getDbh();
	$dbh->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES euckr");

	#insertQuery ¼³Á¤
	$insertQuery  = "INSERT INTO pay_data ( paymentIdx, payTableKey, payMethod, payAmount, ";
	$insertQuery .= "payStatus, serviceName, productCode, parentId, userId, orderName, orderNo, ";
	$insertQuery .= "pgId, tradeNo, approvalNo, bankName, accountNo, accountName, escrowYN, escrowNo, ";
	$insertQuery .= "mobileNo, regDate, bankCheckIdx, updateDate, testFlag, refundIdx, cancelKey, ";
	$insertQuery .= "managerId, devRemark, remark) VALUES( ";
	$insertQuery .= ":paymentIdx, :payTableKey, :payMethod, :payAmount, ";
	$insertQuery .= ":payStatus, :serviceName, :productCode, :parentId, :userId, :orderName, :orderNo, ";
	$insertQuery .= ":pgId, :tradeNo, :approvalNo, :bankName, :accountNo, :accountName, :escrowYN, :escrowNo, ";
	$insertQuery .= ":mobileNo, :regDate, :bankCheckIdx, :updateDate, :testFlag, :refundIdx, :cancelKey, ";
	$insertQuery .= ":managerId, :devRemark, :remark )";

	$cnt = 1;

	while(true){

		if( $cnt == 100 ){
			echo 'cnt = 100'.PHP_EOL;
			break;
		}
		$cnt++;

		$query = "SELECT paymentIdx FROM pay_data ORDER BY idx DESC LIMIT 1 ";
		$stmt = $dbh->prepare($query);
		$stmt->execute();
		$payData = $stmt->fetch(PDO::FETCH_ASSOC);
		$paymentIdx = $payData['paymentIdx'];



		$getDataUrl  = 'https://billingadmin.cafe24.com/test/ykkim02/test_get_pay_data.php';
		$getDataUrl .= '?paymentIdx='.$paymentIdx;

		$sslFlag = Net::getSSLFlag( $getDataUrl );

		$getData = Net::getHtml( $getDataUrl, 'GET', 30, TRUE );
		$getData = unserialize($getData);

		echo 'last paymentIdx='.$paymentIdx.PHP_EOL;

		if( empty( $getData ) == true ){
			echo 'END '.PHP_EOL;
			exit;
		}


		$stmt = $dbh->prepare($insertQuery);
		$stmt->bindParam( ':paymentIdx', $paymentIdx, PDO::PARAM_INT );
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

			$serviceName 	= iconv( 'euc-kr', 'utf-8', $row['serviceName'] );
			$orderName		= iconv( 'euc-kr', 'utf-8', $row['orderName'] );
			$bankName		= iconv( 'euc-kr', 'utf-8', $row['bankName'] );
			$remark			= iconv( 'euc-kr', 'utf-8', $row['remark'] );

			echo 'paymentIdx='.$key.PHP_EOL;

			$paymentIdx	 	= $key;
			$payTableKey 	= $row['payTableKey'];
			$payMethod 		= $row['payMethod'];
			$payAmount		= $row['payAmount'];
			$payStatus		= $row['payStatus'];
			$serviceName	= $serviceName;
			$productCode	= $row['productCode'];
			$parentId		= $row['parentId'];
			$userId			= $row['userId'];
			$orderNo		= $row['orderNo'];
			$pgId			= $row['pgId'];
			$tradeNo		= $row['tradeNo'];
			$approvalNo		= $row['approvalNo'];
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
			$remark			= $remark;

			$result = $stmt->execute();

			if( $result == false ){
				echo 'Insert Error | payTableKey='.$payTableKey.PHP_EOL;
			}

			if( $productCode == 'NONE' ){
				echo 'getData was empty | paymentIdx='.$paymentIdx.' | payTableKey='.$payTableKey.PHP_EOL;
			}
		}

		unset( $getData, $stmt );
	}
	unset( $dbh );
	exit;


} catch ( Exception $e ) {
	echo '<pre>' . print_r ( $e, true ) . '</pre>';
}

?>