<?php
class Net{

    public function __construct() {}
    public function __destruct() {
        unset($this);
    }

    /**
     * example
     *

    $url="http://adsa.cafe24.com/test.php?fsdfs=fsdfs&fsdf=4sfd"
    include('library.php/util/util.net.class.php');
    $data=Net::getHtml($url);
    echo($data);

    $url="http://adsa.cafe24.com/test.php?fsdfs=fsdfs&fsdf=4sfd"
    include('library.php/util/util.net.class.php');
    $data=Net::getHtml($url,'POST');
    echo($data);
    */

    static public function getHtml($url,$method='GET',$timeout=3,$sslFlag=false,$port=null){

        $billingServerIpList = array(
            '222.122.210.198', // apibilling.cafe24.com
            '222.122.87.118',  // billing.simplexi.com
            '222.122.86.243',  // billiingadmin.cafe24.com
            '222.122.84.114',  // deposit.cafe24.com
            '222.122.87.38',   // finance.cafe24.com
            '222.122.210.241', // pg.cafe24.com
            '222.122.210.240', // pg-qa.cafe24.com
            '222.122.210.249', // pgcontrol.cafe24.com
            '211.196.153.42',  // tax.cafe24.com
            '203.231.63.91',   // pg.cafe24test.com <-- test.billing과 framework을 공유할 것을 고려하여 일단 추가함
            '222.122.210.243', // pg-001.cafe24.com
            '222.122.210.246', // pg-002.cafe24.com
        );

        $parsedData=parse_url($url);
        if($parsedData==false){
            return false;
        }
        $scheme=$parsedData['scheme'];
        $host  =$parsedData['host'];
        $path  =$parsedData['path'];
        $query =(isset($parsedData['query']) == true) ? $parsedData['query'] : '';

        $param=NULL;
        if( empty($query)==false ){
            $param=array();
            $params=explode('&',$query);
            for( $k=0;$k<count($params);$k++ ){
                $tempData=NULL;
                $tempArray=NULL;
                $tempData=explode('=',$params[$k]);
                $param[current($tempData)]=end($tempData);
            }
        }

        if( $method=='POST' ) {
           $initUrl = $host.$path;
        } else {
            $method = 'GET';
            $initUrl = $host.$path;
            if(empty($query) == false) {
                $initUrl .='?'.$query;
            }
        }

        if( $sslFlag==true ) $sslFlag=true;
        else                 $sslFlag=false;

        if( $sslFlag==true ) $curl=curl_init('https://'.$initUrl);
        else                 $curl=curl_init('http://'.$initUrl);

        if( $method=='POST' ) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
        } elseif( $method=='GET' ) {
            curl_setopt($curl, CURLOPT_HTTPGET, 1);
        }

        if( is_null($port)==false ) curl_setopt($curl, CURLOPT_PORT, intval($port));

        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        //curl_setopt($curl, CURLOPT_FAILONERROR, 1);

        if( $sslFlag==true ){
        	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        	/*
            if ( isset( $_SERVER['SERVER_ADDR'] ) == true && in_array( $_SERVER['SERVER_ADDR'], $billingServerIpList ) == true ) {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            } else {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
                //curl_setopt($curl, CURLOPT_SSLVERSION, 3);
                //curl_setopt($curl, CURLOPT_CAINFO, "/home/cafe24/framework/etc/cacert.pem");
            }
            */
        }

        $data    =curl_exec($curl);
        $errNo   =curl_errno($curl);
        $errMsg  =curl_error($curl);
        $curlInfo=curl_getinfo($curl);
        curl_close($curl);
        unset($curl);

        /*
            ## 데이터 리턴 로직 분기에 대한 설명 ##

            service.billing의 Net 클래스를 service.framework의 Net 클래스와 병합하는 과정에서,
            curl_getinfo()['http_code']의 값이 '200'이 아닌 경우에 대한 리턴 값이 다른 것이 문제.

            종음님과 상의한 결과,
            일단은 service.billing의 Net 클래스를 사용하는 서버인지 여부에 따라
            리턴 값을 기존대로 사용할 수 있도록 분기하기로 함.

            추후, 현재의 getHtml 메서드의 문제점(리턴 값에 대한 이슈)을 보완한 새로운 메서드 제작 예정
        */

        if ( isset( $_SERVER['SERVER_ADDR'] ) == true && in_array( $_SERVER['SERVER_ADDR'], $billingServerIpList ) == true ) {

            if ( strval( $errNo ) === '0' ) {
                if ( $curlInfo['http_code'] == '200' ) {
                    return $data;
                } else {
                    return 'data='.$data.' | '.'curlInfo='.print_r($curlInfo, true);
                }
            } else {
                $error='curErrNo='.$errNo.' | '.'curErrMsg='.$errMsg.' | '.'data='.$data.' | '.'curlInfo='.print_r($curlInfo, true);
                throw new Exception($error, 90000);
            }

        } else {

            if ( strval( $errNo ) === '0' ) {
                if ( $curlInfo['http_code'] == '200' ) {
                    return $data;
                } else {
                    return NULL;
                }
            } else {
                $errMsg='errNo='.$errNo.' | '.'errMsg='.$errMsg.' | '.'curlInfo='.implode('|', $curlInfo);
                throw new Exception($errMsg, 90000);
                //return NULL;
            }
        }
    }

    static public function getHtmlPost($url, $param, $timeout=3, $sslFlag=false, $port=null){
        if(empty($url) == true) {
            return false;
        }

        $parsedData=parse_url($url);
        if($parsedData==false){
            return false;
        }
        $scheme=$parsedData['scheme'];
        $host  =$parsedData['host'];
        $path  =$parsedData['path'];
        $query =(isset($parsedData['query']) == true) ? $parsedData['query'] : '';

        $initUrl = $host.$path.'?'.$query;

        if( $sslFlag==true ) $curl=curl_init('https://'.$initUrl);
        else                 $curl=curl_init('http://'.$initUrl);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $param);

        if( is_null($port)==false ) curl_setopt($curl, CURLOPT_PORT, intval($port));

        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);

        if( $sslFlag==true ){
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            //curl_setopt($curl, CURLOPT_SSLVERSION, 3);
            curl_setopt($curl, CURLOPT_CAINFO, "/home/cafe24/framework/etc/cacert.pem");
        }

        $data    =curl_exec($curl);
        $errNo   =curl_errno($curl);
        $errMsg  =curl_error($curl);
        $curlInfo=curl_getinfo($curl);
        curl_close($curl);
        unset($curl);

        $result = array();
        try {
            if(strval($errNo) === '0') {
                if($curlInfo['http_code'] == '200') {
                    $result['resultCode'] = 'SUCC';
                    $result['resultData'] = $data;
                } else {
                    throw new Exception('http 코드 장애', 1000);
                }
            } else {
                throw new Exception('통신 에러', 1000);

            }
        } catch(Exception $e) {
            $result['resultCode'] = 'FAIL';
            $result['resultData'] = $curlInfo;
            $result['curlErrorMsg'] = $errMsg;
            $result['curlErrorCode'] = $errNo;
            $result['errorMsg'] = $e->getMessage();
        }
        return $result;
    }


    /**
     * example
     *

    $query='simplexi.com';
    include('library.php/util/util.net.class.php');
    $data=Net::whois($query);
    echo($data);

    $url='123.140.248.150';
    include('library.php/util/util.net.class.php');
    $data=Net::whois($query,10);
    echo($data);
    */
    static public function whois($query,$timeout=10){

        $result='';
        if( empty($query)==true ) return $result;

        $url='http://whois.nida.or.kr/result.php?domain_name='.$query;
        $data=Net::getHtml($url,'POST',$timeout);

        $dataSPos=stripos($data, "<pre>");
        $dataEPos=stripos($data, "</pre>");
        $data=substr($data,$dataSPos,$dataEPos+6-$dataSPos);
        $result.='[whois.nida.or.kr]';
        $result.=$data;

        $url='http://wq.apnic.net/apnic-bin/whois.pl?searchtext='.$query;
        $data=self::getHtml($url,'POST',$timeout);

        $dataSPos=stripos($data, "<pre>");
        $dataEPos=stripos($data, '<div class="highlight" id="key">');
        $data=substr($data,$dataSPos,$dataEPos-$dataSPos);
        $data=strip_tags($data);
        $data='<pre>'.$data.'</pre>';
        $result.='[APNIC - Query the APNIC Whois Database]';
        $result.=$data;

        return $result;
    }


    static public function getSSLFlag( $URL )
    {
        if ( parse_url( $URL, PHP_URL_SCHEME ) == 'https' ) {
            return true;
        } else {
            return false;
        }
    }
}
?>