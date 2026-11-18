<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use Omnipay\Omnipay;

require_once(APPPATH . 'third_party/omnipay/vendor/autoload.php');

class Paymongo {

    private $_CI;

    public function __construct() {
        $this->_CI = &get_instance();
    }

    public function payment() {
        $gateway = Omnipay::create('Paymongo_Card');
        
        $publicKey = getenv('PAYMONGO_PUBLIC_KEY') ? getenv('PAYMONGO_PUBLIC_KEY') : '';
        $secretKey = getenv('PAYMONGO_SECRET_KEY') ? getenv('PAYMONGO_SECRET_KEY') : '';

        if (!empty($publicKey) && !empty($secretKey)) {
            $gateway->setKeys($publicKey, $secretKey);
        }
    }

}
