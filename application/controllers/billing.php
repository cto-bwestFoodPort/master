<?php
class Billing extends MY_Controller
{
	private $payHub = null;
	private $response = null;
	private $totals = null;
	private $gateway = null;

	public function __construct(){
		parent::__construct();
		$this->load->library('omnipay_call', $params = array('PayPal_Express', TEST_USERNAME, TEST_PASS, TEST_SIG, TRUE));
		$this->load->model('order_model');
		$this->gateway = $this->omnipay_call->getGateway();
		$this->totals = $this->order_model->getOrderTotals();
	}

	public function do_purchase(){
		$purchData = [
        	'amount' => $this->totals['total'],
        	'currency' => 'USD',
        	'description' => "Food Port Delivery Service",
        	'returnUrl' => 'http://'.$_SERVER['SERVER_NAME'] . '/foodport/billing/purchase_success',
        	'cancelUrl' => 'http://'.$_SERVER['SERVER_NAME'] . '/foodport/billing'
        ];

        $this->response = $this->gateway->purchase($purchData)->send();

        if ($this->response->isSuccessful()) {
		    // payment is complete
		} elseif ($this->response->isRedirect()) {
		    $this->response->redirect(); // this will automatically forward the customer
		} else {
		    // not successful
		}
    }

	public function do_refund(){

	}

	public function purchase_success(){
		$payData = $this->extractURLValues();
		$this->order_model->saveOrder($this->cart->contents());

		// $purchData = [
		// 	'amount' => $this->totals['total'],
  //       	'currency' => 'USD',
  //       	'description' => "Food Port Delivery Service",
  //       	'returnUrl' => 'http://'.$_SERVER['SERVER_NAME'] . '/foodport/billing/billing_success',
  //       	'cancelUrl' => 'http://'.$_SERVER['SERVER_NAME'] . '/foodport/billing/billing_failed',
  //       	'token' => $payData['token'],
  //       	'PayerID' => $payData['PayerID']
		// ];

		// $this->response = $this->gateway->completePurchase($purchData)->send();
		
		// print_r($this->response->getData());
	}

	public function billing_success(){
		echo "SUCCESS!";
	}

	public function billing_failed(){
		echo "FAILED!";
	}

	//Because we can't use $_GET in codeigniter
	public function extractURLValues(){
		 parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET);

		 return $_GET;
	}
}