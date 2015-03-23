<?php
try {
	echo 'test API call';

	require_once 'lib/net.class.php';

	$url  = 'https://billingadmin.cafe24.com/test/ykkim02/test_api.php';
	$url .= '?id=ykkim02&date=2015';

	$sslFlag = Net::getSSLFlag( $url );

	$getData = Net::getHtml( $url, 'GET', 30, TRUE );

	var_dump( $getData );


} catch ( Exception $e ) {
	echo '<pre>' . print_r ( $e, true ) . '</pre>';
}

?>