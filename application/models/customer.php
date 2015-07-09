<?php
    class Customer extends CRUD_model {

        protected $_table = "customers";
        protected $_primary_key = "cust_id";

        public $first_name;
        private $user_id = GUEST_ID; //Guest user_id
        public $cust_id;
        public $last_name;
        private $addr_id;
        private $phone_prefix;
        private $phone_suffix;
        private $phone_postfix;
        public $phone;
        private $ip_address;
        
        public function __construct() {
            parent::__construct();
            
            $this->first_name = $this->input->post('f_name');
            $this->last_name = $this->input->post('l_name');
            $this->phone_prefix = $this->input->post('phone1');
            $this->phone_suffix = $this->input->post('phone2');
            $this->phone_postfix = $this->input->post('phone3');
            $this->ip_address = $_SERVER['REMOTE_ADDR'];
                        
            $this->assemble_phone();
        }
        
        private function assemble_phone(){
            $this->phone = '(' . $this->phone_prefix . ')-' . $this->phone_suffix . '-' . $this->phone_postfix;      
        }
        
        public function set_userId($user_id){
            $this->user_id = $user_id;
        }

        public function getCust($id){
            return parent::get($id);
        }
        
        public function add_cust(){
            $address = new Address();
            $this->addr_id = $address->add_addr();
            $cust_data = array(
                "first_name"=>$this->first_name,
                "last_name"=>$this->last_name,
                "primary_phone"=>$this->phone,
                "ip_address"=>$this->ip_address,
            );
            
            //Set session values for the guest
            $this->session->set_userdata('first_name', $this->first_name);
            $this->session->set_userdata('last_name', $this->last_name);
            $this->session->set_userdata('phone', $this->phone);

            //Save the information to the database.
            $this->db->insert('customers', $cust_data);
            $this->cust_id = $this->db->insert_id();
            $this->session->set_userdata('cust_id', $this->cust_id);

            //Lets link the customer with the address and guest id if they're not logged in
            $this->_table = "cust_user_addr";
            parent::insert(["user_id"=>$this->user_id, "cust_id"=>$this->cust_id, "address_id"=>$this->addr_id]);
        }
        
        public function get_custId(){
            return $this->cust_id;
        }

        public function updateCustomer(){
            //If customer id is set, that means a customer has already been recorded and just needs updated.
            //Otherwise we add a new customer.
            if($this->session->userdata('cust_id'))
            {
                //First we'll need to get the link for updating the address.
                $this->load->model('cust_user_addr_model');
                $linkData = [
                    "cust_id" => $this->session->userdata('cust_id'),
                ];

                $linkResults = $this->cust_user_addr_model->getLinks($linkData);
                if(count($linkResults) > 0){
                    $address = new Address();

                    foreach($linkResults as $link){
                        $address->updateAddr($link['address_id']);

                        $cust_data = array(
                            "first_name"=>$this->first_name,
                            "last_name"=>$this->last_name,
                            "primary_phone"=>$this->phone,
                            "ip_address"=>$this->ip_address,
                        );

                        //Reset session values for the guest
                        $this->session->set_userdata('first_name', $this->first_name);
                        $this->session->set_userdata('last_name', $this->last_name);
                        $this->session->set_userdata('phone', $this->phone);

                        //Save the information to the database.
                        parent::update($cust_data, $this->session->userdata('cust_id'));
                    }
                }
            }else
            {
                echo "adding customer";
                $this->add_cust();
            }
        }
    }