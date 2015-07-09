<?php

class Emp_rest_linkr extends CRUD_Model
{
	protected $_table = "emp_rest_distance";
	protected $_primary_key = "link_id";

	public function __construct(){
		parent::__construct();
	}

	public function newLink($data){
		return parent::insert($data);
	}
}