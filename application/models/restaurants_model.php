<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of restaurants_model
 *
 * @author Admin
 */
class restaurants_model extends CRUD_model {
	public $id;
	public $name;
	public $logo;
	public $category;
	public $addr_id;
	public $address;
	public $city;
	
	private $tableData;

	protected $_table = "restaurants";
	protected $_primary_key = "rest_id";
	
	public function add_restaurant($addr_id) {
		$this->tableData['name'] = $this->name = $this->input->post('rest_name');
		$this->tableData['logo_loc'] = $this->logo = $this->input->post('logo_loc');
		$this->tableData['cat_id'] = $this->category = $this->input->post('rest_cat');
		
		//Previously created in the db by the addresses model.
		$this->tableData['addr_id'] = $this->addr_id = $addr_id;
		
		$this->db->trans_begin();
		try{
			$this->db->insert('restaurants', $this->tableData);
			$this->id = $this->db->insert_id();

			//Create the view
			$q = "CREATE OR REPLACE VIEW ".str_replace([" ", "'","_&_"], ["_", "", "_"], strtolower($this->name))."_".$this->id;
			$q .= " AS SELECT fc.name, fi.* ";
			$q .= "FROM food_cat as fc, food_items as fi, restaurants as rest ";
			$q .= "WHERE fc.rest_id = rest.rest_id AND fi.fdcat_id = fc.fdcat_id AND rest.rest_id =" . $this->id;
			
			$this->db->query($q);
		}catch(Exception $e)
		{
			echo $e->getMessage();
		}
		
		if($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}else {
			$this->db->trans_commit();
			return $this->id;
		}
	}
	
	public function exportToJson(&$collection) {
	    file_put_contents("./assets/json/restaurants.json", json_encode($collection['restaurants']));
	}
	
	public function getFoodCategories($rest_id){
		$this->_table = "food_cat";
		return parent::get(array("rest_id" => $rest_id));
	}

	public function addFoodCategory($data){
		$this->_table = "food_cat";
		return parent::insert($data);
	}

	public function addFoodItem($data){
		$this->_table = "food_items";
		return parent::insert($data);
	}

	public function getMenuItems($table)
	{
		$this->_table = $table;
		$results = parent::get();

		for($i = 0; $i < Count($results); $i++)
		{
			$string = $results[$i]['price'];
			$results[$i]['price'] = unserialize($string);
		}
		return $results;
	}

	public function getAllRestaurants($city = null){
		if(null == $city){
			$city = (null !== $this->input->post('ajaxcity')) ? $this->input->post('ajaxcity') : null;
		}
		$restaurants = parent::get();
		$this->load->model("address");
		$address = new Address();
		foreach($restaurants as $key => $currRest){
			$rest_addr = $address::get_addr($currRest['addr_id']);

			$restaurants[$key]['address'] = $rest_addr->toString();

			if($city !== null && $city !== " " && (!empty($city))){
				echo "city: ".$city;
				$currCity = $rest_addr->get_city();
				if($currCity !== $city){
					unset($restaurants[$key]);
				}
			}
		}
		return json_encode($restaurants);
	}

	public function getRestaurant($id){
		return parent::get($id);
	}
	
    public function load_restaurants(&$collection){
    	
    	$query = $this->db->get('restaurants');
    	
    	foreach($query->result() as $result)
    	{
    		$restaurant = new restaurants_model();
    		$restaurant->id = $result->rest_id;
    		$restaurant->name = $result->name;
    		$restaurant->logo = $result->logo_loc;
    		$restaurant->category = Restcat_model::getCatById($result->cat_id);
    		$restaurant->address = Address::get_addr($result->addr_id);
    		$restaurant->city = $restaurant->address->get_city();
    		$restaurant->zip = $restaurant->address->get_zip();
    		$restaurant->address = $restaurant->address->toString();
    		
    		$collection['restaurants'][] = $restaurant;
    	}
    }
}