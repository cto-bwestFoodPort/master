<?php
class Restcat_model extends CI_Model {
	private $cat_name;
	private $cat_ids;
	
	public function __construct(){
		parent::__construct();
		
		$this->cat_name = array();
		$this->getCatNames();
	}
	
	public function getCatNameArray() {
		return $this->cat_name;
	}
	
	public function getCatIdArray() {
		return $this->cat_ids;
	}
	
	public function getCatNames() {
		$query = $this->db->get('rest_cat');
		
		if($query->num_rows() > 0) {
			foreach($query->result() as $category) {
				$this->cat_name[] = $category->cat_name;
				$this->cat_ids[] = $category->cat_id;
			}
		}
	}
	
	public static function getCatById($id){
		$CI = &get_instance();
		$query = $CI->db->get_where('rest_cat', array('cat_id'=>$id));
		
		foreach($query->result() as $result)
		{
			return $result->cat_name;
		}
	}
}