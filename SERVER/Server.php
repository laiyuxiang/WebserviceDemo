<?php
/**
 * Created by PhpStorm.
 * User: rex(腾飞的鱼)
 * Date: 2017/7/5 0005
 * Time: 下午 1:52
 */

define('SOAP_MODEL', 1);// 1:wsdl模式  2:no-wsdl模式
include_once '../SERVER/SoapDiscovery.php';
include_once '../SERVER/ServerImp.php';
$soap = new SoapDiscovery('ServerImp','ServerImp');

// 创建 WSDL 服务
//如果是客户端调用会访问两次此处，第一次是get用来生成wsdl文件，第二次是post用来调用webservice服务
//如果是链接访问第一次会生成wsdl并且在页面输出wsdl文件内容，第二次访问就直接输出wsdl文件内容
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    @set_time_limit(3000);
    @ini_set('memory_limit', '-1');
    $options['cache_wsdl'] = WSDL_CACHE_MEMORY;
    if (SOAP_MODEL == '1') {
        $wsdl = 'ServerImp.wsdl';
        // WSDL 模式不用传 uri 参数，但传了也不会有问题
    } else {
        $options['uri'] = 'http://'.$_SERVER['SERVER_NAME'];
        $wsdl = null;
    }
    try {
        $server = new SoapServer($wsdl, $options);
        $server->setClass('ServerImp');
        $server->handle();
    }catch (SoapFault $fault){
        echo 'Error Message5: ' . $fault->getMessage();
    }
} else {
    // 查看 WSDL xml，删除以下程序就相当于 non-WSDL 模式
       header('Content-type: text/xml');
    if (isset($_SERVER['QUERY_STRING']) &&
        (strcasecmp($_SERVER['QUERY_STRING'], 'wsdl') == 0 || strpos($_SERVER['QUERY_STRING'], 'wsdl') !== false)) {
        echo $soap->getWSDL();
    } else {
        echo file_get_contents('ServerImp.wsdl');
    }
}
