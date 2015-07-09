<?php if (!defined('BASEPATH'))exit('No direct script access allowed');
require_once(APPPATH. 'third_party/vendor/autoload.php');
use Omnipay\Omnipay;


class Omnipay_call extends Omnipay {
	private $gateway = null;

	public function __construct($params = array()) {
		list($gateway, $user, $pass, $signature, $test) = $params;
        $this->gateway = Omnipay::create($gateway);
        $this->gateway->setUsername($user);
        $this->gateway->setPassword($pass);
        $this->gateway->setSignature($signature);
        $this->gateway->setTestMode($test);
    }

    public function getGateway(){
        return $this->gateway;	
    }
}