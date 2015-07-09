<?php
	class Cust_user_addr_model extends CRUD_model
	{	
		protected $_table = "cust_user_addr";
		public function __construct()
		{
			parent::__construct();
		}

		public function getLinks($data){
			return parent::get($data);
		}
	}