<?php
class Order_model extends CRUD_model
{
	protected $_table = "orders";
	protected $_primary_key = "order_id";

	public function __construct(){
		parent::__construct();
		$this->load->library('cart');
		$this->load->helper("url");
	}

	public function saveOrder(){
		

		$data = [
			'cust_id' => $this->session->userdata('cust_id')
		];

		//First collect the link id.
		$this->_table = "cust_user_addr";
		$linkTable = parent::get($data);
		$link = $linkTable[0]['link_id'];

		//Now save all data in the orders table.
		$orderDta = [
			'link_id' => $link,
			'order_data' => serialize($this->cart->contents()),
			'order_status' => 'ACTIVE'
		];

		$this->_table = "orders";
		parent::insert($orderDta);
	}

	public function updateOrderStatus(){

	}

	public function addCartItem($data){
		if($this->cart->insert($data))
		{
			return true;
		}else
		{
			return false;
		}
	}

	public function numCartItems(){
		return $this->cart->total_items();
	}

	public function emptyCart(){
		$this->cart->destroy();
	}

	public function updateCart($data)
	{
		$this->cart->update($data);
	}

	public function getOrderTotals(){
		$cart_total = $this->cart->total();
		$restIds = (array) new stdClass();
		$addresses = [];

		foreach($this->cart->contents() as $items){
			if($this->cart->has_options($items['rowid'])){
				$optArr = $this->cart->product_options($items['rowid']);
				if(!in_array($optArr['rest_id'], $restIds)){
					$restIds[] = $optArr['rest_id'];
				}
				if(!isset($optArr['paired'])){
					$address = $optArr['rest_addr']['addr1'];
					$address .= $optArr['rest_addr']['addr2'] != "" ? " ".$optArr['rest_addr']['addr2'] : "";
					$address .= " ".$optArr['rest_addr']['city'];
					$address .= " ".$optArr['rest_addr']['state'];
					$address .= " ".$optArr['rest_addr']['zip'];

					if(!in_array($address, $addresses)){
						$addresses['addresses'][] = $address;
						$addresses['rowids'][] = $items['rowid'];
					}
				}
			}
		}

		$delivery_address = $this->session->userdata('deliveryAddress');

		$destinationString = "";

		foreach($addresses['addresses'] as $addr){
			if($destinationString == ""){
				$destinationString = $addr;
			}else
				$destinationString .= "|".$addr;
		}
		$url = DISTANCE_URL .'json?origins='.urlencode($delivery_address).'&destinations='.urlencode($destinationString).'&units=imperial&key='.GOOGLE_APIKEY;

		//Get the distances between the stores and the destination.
		$this->load->library('curldriver');
		$distancesArray = $this->curldriver->curlOpt($url);

		//Return the furthest distance for initial mileage charge.
		$this->load->library('fpdistancematrix');
		
		$distanceResults = $this->fpdistancematrix->findFurthestDistance($distancesArray);
		$mileage = 0;

		if(is_numeric($distanceResults)){
			$mileage = $distanceResults;
		}else
		{
			$mileage = $distanceResults['distance'];
			foreach($distanceResults['remove'] as $remove){
				$this->cart->update([
					'rowid' => $addresses['rowids'][$remove],
					'qty' => 0
				]);
			}
		}
			
		//Collect the distances between restaurants in order to tell whether to charge
		//More for mileage as well as whether this is a transport or delivery.

		//TODO: Modify this since the address is now included with every cart item.
		//Get the address zipcode
		$this->load->model("restaurants_model");
		$this->load->model("address");


		$addrId = $this->restaurants_model->getRestaurant($restIds[0]);
		$addrObj = Address::get_addr($addrId[0]['addr_id']);
		$zip = $addrObj->get_zip();

		//Let's load the tax rate model
		$this->load->model("taxrates");

		$tax_rate = (($this->taxrates->getDBTaxRate($zip)) / 100);
		$subtotal = $cart_total;
		$rest_tax = round($cart_total * $tax_rate, 2);

		//Add the restaurant tax to the menu price.
		$total = $cart_total + ($cart_total * $tax_rate);
		$multiRestFee = round(((count($restIds)-1) * MULTI_REST_FEE), 2);
		$mileageFee = ($mileage > 3.0 && $mileage !== 0) ? (($mileage - 3.0) * MILEAGE_FEE) : 0;
		$fee = null;

		//Add the fee to the total
		if($total >= 1 && $total < 15)
		{
			$fee = round(($total * FOURTY_FIVE_PERCENT)+ $multiRestFee, 2);
			$total = ($total + $fee) + $mileageFee;
		}else if($total >= 15 && $total < 25)
		{
			$fee = round(($total * THIRTY_FIVE_PERCENT)+ $multiRestFee, 2);
			$total = ($total + $fee) + $mileageFee;
		}else if($total >= 25 && $total < 50)
		{
			$fee = round(($total * THIRTY_PERCENT)+ $multiRestFee, 2);
			$total = ($total + $fee)  + $mileageFee;
		}else if($total >= 50){
			$fee = round(($total * TWENTY_FIVE_PERCENT)+ $multiRestFee, 2);
			$total = ($total + $fee) + $mileageFee;
		}

		$data = Array(
			"subtotal" => $subtotal,
			"rest_tax" => $rest_tax,
			"fee" => $fee,
			"multi_rest" => $multiRestFee,
			"mileage" => $mileageFee,
			"total" => round($total, 2)
		);

		return $data;
	}
}