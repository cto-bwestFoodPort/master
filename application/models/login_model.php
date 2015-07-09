<?php
    class Login_model extends CRUD_model implements Notifications{
    	protected $_table = 'users';
    	protected $_primary_key = 'user_id';
        public $username;
        public $password;
        private $req_type;
        
        public function __construct() {
            parent::__construct();
            
            $this->username = $this->input->post('username');
            $this->password = sha1($this->input->post('pass'));
            $this->req_type = $this->input->post('req_type');
        }
        //TODO: Secure the password more than it is using SALT.
        public function login(){
        	$data = [
        		"username" => $this->username,
        		"password" => $this->password
        	];

        	$logInfo = $this->get($data);

        	foreach($logInfo as $user)
        	{
    		 	if($user['username'] == $this->username && $user['password'] == $this->password){
    		 		//First set important login variables.
	    			$this->session->set_userdata('username', $this->username);
	    			$this->session->set_userdata('elevation', $user['elevation']);
	    			$this->session->set_userdata('email', $user['email']);
	    			$this->session->set_userdata('user_id', $user['user_id']);
	    			$this->session->set_userdata('notifications', $user['notifications']);

	    			//Now let's get address information and customer information.
	    			$this->load->model('cust_user_addr_model');
	    			$idLinks = $this->cust_user_addr_model->getLinks(["user_id" => $user['user_id']]);

	    			//Let's get the first and last name; there should only be one result.
	    			$cust_id = $idLinks[0]['cust_id'];

	    			$this->load->model('customer');
	    			$customerInfo = $this->customer->getCust($cust_id);

	    			//Should only be one result
	    			$this->session->set_userdata('first_name', $customerInfo[0]['first_name']);
	    			$this->session->set_userdata('last_name', $customerInfo[0]['last_name']);
	    			$this->session->set_userdata('phone', $customerInfo[0]['primary_phone']);

	    			//Now let's get the address
	    			$this->load->model('address');
	    			$address = Address::get_addr($idLinks[0]['address_id']);
	    			$this->session->set_userdata('addr1', $address->get_addr1());
	    			$this->session->set_userdata('addr2', $address->get_addr2());
	    			$this->session->set_userdata('apt', $address->get_apt());
	    			$this->session->set_userdata('city', $address->get_city());
	    			$this->session->set_userdata('state', $address->get_state());
	    			$this->session->set_userdata('zip', $address->get_zip());

        			return true;
	        	}else
	        	{
        			return false;
	        	}
        	}
        }
        
        public function get_notifications($json_notif) {
            if(!is_null($json_notif))
            {
                json_decode($json_notif, true);
            }else
            {
                return array(1=>'You have no notifications');
            }
        }
    }