<?php
/**
 * PHP SOAP Web-service 测试文件
 */

@set_time_limit(3000);
@ini_set('memory_limit', '-1');

include '../SOAP/Client.php';
include_once '../XML/XML2Array.php';
include_once '../XML/Array2XML.php';

$params = array(
    'serverIP' => '127.0.0.1',
    'serverPort' => '80',
    'serverDir' => '/WebserviceDemo/SERVER/Server.php',
	'mode' => 'wsdl',
);

$clientClass = new Client($params);

$client = $clientClass->getClient();

ini_set('date.timezone','Asia/Shanghai');

try{
	header("Content-type:text/html;charset=utf-8");
	$functions = $client->__getFunctions();
	var_dump ($functions);

	$Source = array();
	$Source['Token'] = 'my name id token';

	$result = Array2XML::createXML('Data', $Source);
	$xml = $result->saveXML();

	$result = $client->__soapCall('returnData', array($xml));
	$result = XML2Array::createArray($result);
	print_r($result);
	echo "<br><br>";
}catch (SoapFault $fault){
	echo "\n";
	echo 'Error Message4: ' . $fault->getMessage();
	echo '----------------------------------------';
	echo $client -> __getLastResponse ();
}

