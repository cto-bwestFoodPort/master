<?php

class Alt_Prices_Model extends CRUD_Model
{
	protected $_table = "alt_prices";
	protected $_primary_key = null;

	public function __construct(){
		parent::__construct();
	}

	public function addPromoAndPrices(){
		$this->load->model("promos_model");
		$data = [];
		$postArr = $this->input->post();
		//Begin the transaction here.
		$this->db->trans_start();

		foreach($postArr as $key=>$value){
			if($key == "rule_name"){
				$data["promo_name"] = $value;
			}elseif($key == "rule_type"){
				$data["type"] = $value;
			}elseif($key == "rest_id"){
				$data["rest_id"] = $value;
			}elseif($key == "food_ids"){
				foreach($value as $food_id=>$food_info){
					$foodData[] = [
						"food_id" => $food_id,
						"topics" => serialize($food_info['type']),
						"discount" => serialize($food_info['discount'])
					];
				}
			}
		}
		try{
			$promo_id = $this->promos_model->addPromo($data);
		}catch(Exception $ex){
			show_error($ex->getMessage());
		}

		foreach($foodData as &$food){
			$food['promo_id'] = $promo_id;
		}

		parent::insert($foodData);

		$this->db->trans_complete();
	}

	public function getFoodLinks($data){
		return parent::get($data);
	}
}