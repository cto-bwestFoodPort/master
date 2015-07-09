<?php
    class CP extends MY_Controller {
        public $layout = 'layouts/_cpLayout';
        public $content = '/cp/main_content';
        
        public function __construct() {
            parent::__construct($this->layout);
			$this->load->helper("url");
			$this->load->helper("html");
        }
        
        public function index(){
			$data["username"] = $this->username;
			$data["main_content"] = $this->content;
			
			//Check to see that the user is an admin and not a guest or other
			if($this->session->userdata('elevation') == "NORMAL") {
				redirect(site_url());
			}
            $this->load->view($this->layout, $data);
        }
    }
