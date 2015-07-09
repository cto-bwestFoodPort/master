<?php
    class Accounts extends MY_Controller{
        public $layout = '/layouts/_accountLayout';
        
        public function __construct() {
            parent::__construct($this->layout);
            $this->load->helper('url');
            $this->load->helper('html');
        }
        
        public function index()
        {
            if($this->username == "Guest")
            {
                redirect(site_url());
            }else
            {
				//Collect all user data from the session; defined in the login model
                $data['username'] = $this->username;
                $data['first_name'] = $this->session->userdata('first_name');
                $data['last_name'] = $this->session->userdata('last_name');
                $data['addr1'] = $this->session->userdata('addr1');
                $data['addr2'] = $this->session->userdata('addr2');
                $data['apt'] = $this->session->userdata('apt');
                $data['city'] = $this->session->userdata('city');
                $data['state'] = $this->session->userdata('state');
                $data['zip'] = $this->session->userdata('zip');
                $data['phone'] = $this->session->userdata('phone');
                $data['email'] = $this->session->userdata('email');
                $this->load->view($this->layout, $data);
            }
        }
        
    }
