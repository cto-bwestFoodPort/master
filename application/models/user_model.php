<?php
    class User_model extends CI_Model {
        public $username;
        public $password;
        public $promos;
        public $email;
        
        public function __construct() {
            parent::__construct();
            
            $this->username = $this->input->post('username');
            $this->password = sha1($this->input->post('pass'));
            $this->email = $this->input->post('email');
            
            //Check to see if promos is true or false
            $this->promos = ($this->input->post('promos') != 'true') ? 'false' : 'true';
        }
        
        public function add_user($data)
        {
            if($this->db->insert('users', $data)){
            	return true;
            }else {
            	return false;
            }
        }
        
        private function obtain_user_info($table, $params, $assoc = false){
            $query = $this->db->get_where($table, $params);
            
            if(!$assoc){
                $results = $query->result();
            }else
            {
                $results = $query->row_array();
            }
            
            return $results;
        }
        
        public function get_username($username)
        {
            $results = $this->obtain_user_info('users', array('username'=>$username));
            
            if(count($results) > 0){
                return false;
            }else
            {
                return true;
            }
        }
        
        public function get_userId(){
            $results = $this->obtain_user_info('users', array('username'=>$this->username), true);
            
            if(count($results) > 0)
            {
                return $results['user_id'];
            }else
            {
                return GUEST_ID;
            }
        }
        
        public function update_link_table($user_id, $cust_id, $address_id){
            echo "In " . __FUNCTION__;
            $link_data = array(
                "user_id" => $user_id,
                "cust_id" => $cust_id,
                "address_id" =>$address_id,
            );
            try{
            	$this->db->trans_begin();
            	$this->db->insert('cust_user_addr', $link_data);
            	if($this->db->trans_status() === FALSE){
            		$this->db->trans_rollback();
            		return false;
            	}else
            	{
            		$this->db->trans_commit();
            		return true;
            	}
            }catch(Exception $e)
            {
            	throw $e;
            }
        }
    }