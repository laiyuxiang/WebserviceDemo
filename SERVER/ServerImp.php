<?php
/**
 * Created by PhpStorm.
 * User: rex
 * Date: 2017/7/5 0005
 * Time: 上午 10:06
 */
include_once '../XML/XML2Array.php';
include_once '../XML/Array2XML.php';

class ServerImp{
    /**
     * returnData
     * @param string $xml
     * @return string
     * @soap
     */
    public function returnData($xml){
        //防止超时
        @set_time_limit(3000);
        @ini_set('memory_limit', '-1');
        //把客户端传来的xml解析成数组
        $postXmlData = XML2Array::createArray($xml);

        $token = $postXmlData['Data']['Token'];
        $result = array();
        if(!empty($token)){
            $result['token'] = 'server'.$token;
            $result['status'] = 'success';
        }else{
            $result['status'] = 'error';
        }

        $result = Array2XML::createXML('Result',$result);
        $xml = $result->saveXML();

        //返回结果转换成xml
        return $xml;
    }

}


