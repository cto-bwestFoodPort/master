<?php
class Partners extends MY_Controller
{
	public $layout = "/layouts/_blank";
	public $content = "partners/partner_form";
	public $data = [];

	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('html');
		$this->data['username'] = $this->username;
	} 

	public function index(){
		$this->data['main_content'] = $this->content;
		$this->load->view($this->layout, $this->data);
	}
}