<?php

class Promos_model extends CRUD_Model{
	protected $_table = "menu_promos";
	protected $_primary_key = "promo_id";

	public function __construct(){
		parent::__construct();
	}

	public function addPromo($data){
		return parent::insert($data);
	}

	public function getPromos($data){
		return parent::get($data);
	}
}