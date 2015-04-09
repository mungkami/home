<?php

try{

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

	$selectQuery  = "SELECT idx, paymentIdx, payTableKey, payMethod ";
	$selectQuery .= "FROM pay_data ";
	$selectQuery .= "WHERE payTableKey NOT IN ( SELECT distinct( payTableKey ) FROM pay_service_data ) ";
	$selectQuery .= "ORDER BY idx DESC LIMIT 10000 ";

	$stmt = $dbh->prepare( $selectQuery );
	$stmt->execute();
	$payDataList = $stmt->fetchAll(PDO::FETCH_ASSOC);


	#API로 던져서 필요한 정보를 받아온다.
	$apiUrl = 'https://billingadmin.cafe24.com/test/ykkim02/test_get_service_data.php';
	$sslFlag = Net::getSSLFlag($apiUrl);
	$cnt = 1;
	$payTableKeyList = array();
	foreach( $payDataList as $row => $data ){

		$payTableKeyList[] = $data['payTableKey'];
		if( ( $cnt % 1 ) == 0 ){
			#10개 단위로 payTableKey를 넘김
			$payTableKey = implode( "|^|", $payTableKeyList );
			echo $cnt.PHP_EOL;

			#API콜
			$getDataUrl = $apiUrl.'?payTableKey='.$payTableKey;
			$getData = Net::getHtml($getDataUrl, 'POST', 30, $sslFlag );

			$getData = unserialize( $getData );

			var_dump( $getData );

			foreach( $getData as $key => $val ){
				$query  = "INSERT INTO pay_service_data( payTableKey, serviceIdx, parentId, userId, ";
				$query .= "payerId, status, chargedMethod, chargedAmount, chargedDate, startDate, endDate, ";
				$query .= "serviceKind, serviceNameIdx, period, taxInvoiceNo, receiptNo, refundNo ) VALUES( ";
				$query .= ":payTableKey, :serviceIdx, :parentId, :userId, ";
				$query .= ":payerId, :status, :chargedMethod, :chargedAmount, :chargedDate, :startDate, :endDate, ";
				$query .= ":serviceKind, :serviceNameIdx, :period, :taxInvoiceNo, :receiptNo, :refundNo )";

				$stmt = $dbh->prepare( $query );
				$stmt->bindParam(':payTableKey', $payTableKey, PDO::PARAM_STR );
				$stmt->bindParam(':serviceIdx', $serviceIdx, PDO::PARAM_STR );
				$stmt->bindParam(':parentId', $parentId, PDO::PARAM_STR );
				$stmt->bindParam(':userId', $userId, PDO::PARAM_STR );
				$stmt->bindParam(':payerId', $payerId, PDO::PARAM_STR );
				$stmt->bindParam(':status', $status, PDO::PARAM_STR );
				$stmt->bindParam(':chargedMethod', $chargedMethod, PDO::PARAM_STR );
				$stmt->bindParam(':chargedAmount', $chargedAmount, PDO::PARAM_STR );
				$stmt->bindParam(':chargedDate', $chargedDate, PDO::PARAM_STR );
				$stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR );
				$stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR );
				$stmt->bindParam(':serviceKind', $serviceKind, PDO::PARAM_STR );
				$stmt->bindParam(':serviceNameIdx', $serviceNameIdx, PDO::PARAM_STR );
				$stmt->bindParam(':period', $period, PDO::PARAM_STR );
				$stmt->bindParam(':taxInvoiceNo', $taxInvoiceNo, PDO::PARAM_STR );
				$stmt->bindParam(':receiptNo', $receiptNo, PDO::PARAM_STR );
				$stmt->bindParam(':refundNo', $refundNo, PDO::PARAM_STR );

				$payTableKey 	= $val['service_log_idx'];
				$serviceIdx 	= $val['idx'];
				$parentId 		= $val['parent_id'];
				$userId 		= $val['user_id'];
				$payerId 		= $val['payer_id'];
				$status 		= $val['status'];
				$chargedMethod 	= $val['charged_method'];
				$chargedAmount 	= $val['charged_amount'];
				$chargedDate 	= $val['charged_date'];
				$startDate 		= $val['start_date'];
				$endDate 		= $val['end_date'];
				$serviceKind 	= $val['service_kind'];
				$serviceNameIdx = $val['service_name_idx'];
				$period 		= $val['period'];
				$taxInvoiceNo 	= $val['regno'];
				$receiptNo 		= $val['cash_receipt_idx'];
				$refundNo 		= $val['refundno'];

				$insertResult 	= $stmt->execute();

				if( $insertResult == FALSE ){
					echo 'insert error | payTableKey='.$payTableKey;
				}

			}
			sleep(2);
			$payTableKeyList = array();
		}

		$cnt++;
	}
	exit;

}catch(Exception $e){

	echo print_r( $e, true );
	exit;

}