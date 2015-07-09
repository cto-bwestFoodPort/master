<?php
    class MY_Controller extends CI_Controller{
        
        public $layout = null;
        public $username;
        public $elevation = "normal";
        protected $data;
        
        public function __construct()
        {
            parent::__construct();
            
            if($this->session->userdata('username') == '')
            {
                $this->username = 'Guest';
				$this->session->set_userdata("elevation", "NORMAL");

            }else
            {
                $this->username = $this->session->userdata('username');
                $this->elevation = $this->session->userdata('elevation');
            }

            $this->data['username'] = $this->username;
        }

        //This function available to all controllers.
        public function numCartItems(){
            $this->load->model('order_model');
            //TODO: Check to see if this is causing session output errors.
            echo $this->order_model->numCartItems();
        }

        public function emptyCart(){
            $this->load->model('order_model');
            $this->order_model->emptyCart();
        }

        public function verifyAdmin(){
            if($this->session->userdata('elevation') !== "ADMIN"){
                return false;
            }else
                return true;
        }
    }
?>