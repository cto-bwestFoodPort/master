<?php
    class Customers extends MY_Controller{
        public $layout = NULL;
        
        public function __construct() {
            parent::__construct($this->layout);
            
            $this->load->model('user_model');
            $this->load->model('customer');
            $this->load->model('address');
        }
        
        public function add_cust(){
            $customer = new Customer();
            $address = new Address();
            
            $customer->add_cust();
        }

        public function updateCustomer()
        {
            $this->customer->updateCustomer();
        }
    }
