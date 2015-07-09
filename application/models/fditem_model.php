<?php
class FdItem_model extends CRUD_model{
	protected $_table = "food_items";
	protected $_primary_key = "food_id";

	public function __construct(){
		parent::__construct();
	}

	public function getFoodPrice($food_id, $food_spec){
		$results = parent::get($food_id);

		if(count($results) !== 0){
			//We should only ever get back one result.
			$specialties = unserialize($results[0]['price']);
			for($i = 0; $i < count($specialties['topic']); $i++)
			{
				if($food_spec == $specialties['topic'][$i]){
					$price = $specialties['price'][$i];
				}				
			}
		}

		return $price;
	}

	public function getFoodItem($food_id){
		return parent::get($food_id);
	}
}