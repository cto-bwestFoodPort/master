<?php
class Logins extends MY_Controller{
	public $layout = "/layouts/_blank";
	public $main_content = "login/login";
	 
	public function __construct() {
		parent::__construct($this->layout);
		$this->load->model('login_model');
		$this->load->helper('html');
		$this->load->helper('url');
		$this->load->library('cart');
	}

	public function index($error = null){

		$data = array(
			"username" => $this->username,
			"main_content" => $this->main_content
		);
		if(isset($error))
		{
			$data["error"] = constant($error);
		}
		$this->load->view($this->layout, $data);
	}
	 
	public function login_form(){
		$this->load->view('/login/login');
	}
	 
	public function login(){
		$this->load->model('login_model');

		if($this->login_model->login())
		{
			echo'
		    <script>
		    window.location.href = "'.site_url().'";
		    </script>
		    ';
		}else
		{
			redirect(site_url('logins/index/INVALID_LOGIN_MSG'));
		}
	}
	 
	public function noScriptLogin() {
		 $this->load->view('login/login');
	}
	 
	public function logout(){
		$this->cart->destroy();
		$newdata = array(
               "user_id"=>'',
               "username"=>'',
               "email"=>'',
               "first_name"=>'',
               "last_name"=>'',
               "phone"=>'',
			   "elevation"=>'normal',
		);
		 
		$this->session->unset_userdata($newdata);
		$this->session->sess_destroy();
		redirect(site_url());
	}
}