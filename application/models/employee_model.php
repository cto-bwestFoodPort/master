<?php
	class Employee_model extends CRUD_model
	{
		protected $_table = "employee";
		protected $_primary_key = "emp_id";

		public function addEmployee($data){
			return parent::insert($data);
		}

		public function paginationGet($limit, $offset){
			return parent::paginationGet($limit, $offset);
		}

		public function getAllEmployees(){
			return parent::get();
		}
	}