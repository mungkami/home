<?php

#local test getData API

try {
	echo 'test API call';

	require_once 'lib/net.class.php';

	$getDataurl  = 'https://billingadmin.cafe24.com/test/ykkim02/test_get_pay_data.php';

	$sslFlag = Net::getSSLFlag( $getDataurl );

	$getData = Net::getHtml( $getDataurl, 'GET', 30, TRUE );
	$getData = unserialize($getData);

	var_dump( $getData );

} catch ( Exception $e ) {
	echo '<pre>' . print_r ( $e, true ) . '</pre>';
}

?>