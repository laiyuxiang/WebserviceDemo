<?php
/**
 * PHP Soap Client
 */

class Client {

    private $mode = 'wsdl';

    private $trace = true;  // 开启调试

    private $soapVersion = SOAP_1_2;  // SOAP 版本

    private $encoding = 'UTF-8'; // 编码

    private $compression = 0;

    private $options = array();

    private $serverIP = '127.0.0.1';

    private $serverPort = '80';

    private $serverDir = '/Webservice/Collect/index.html';

    private $serviceUri = '';

    public function __construct($params = array()) {
        if (count($params) > 0) {
            foreach ($params as $key => $val) {
                if (isset($this->$key)) {
                    $this->$key = $val;
                }
            }
        }

        $this->options = array(
            'trace' => $this->trace,
            'soap_version' => $this->soapVersion,
            'encoding' => $this->encoding,
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
        		'cache_wsdl' => WSDL_CACHE_NONE
        );

        if ($this->serviceUri == '') {
            $this->serviceUri = 'http://' . $this->serverIP . ':' . $this->serverPort . $this->serverDir;
        }

        if ($this->mode == 'wsdl') {
            $this->serviceUri .= '?wsdl';
        } else {
            $this->options['uri'] = 'http://' . $_SERVER['SERVER_NAME']; // non-WSDL 模式参数
            $this->options['location'] = $this->serviceUri;	// non-WSDL 模式参数，server 端具体路径
            $this->serviceUri = null;
        }

    }

    public function getClient() {
       try {
           return new SoapClient($this->serviceUri, $this->options);
       }catch (SoapFault $fault){
           echo 'Error Message2: ' . $fault->getMessage();
		   exit;
       }
    }
}

