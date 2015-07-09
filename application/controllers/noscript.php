<?php
	class Noscript extends MY_Controller
	{
		public function index(){
			$this->load->helper("url");
			$this->load->helper("html");
			$this->load->view("common/noscript");
		}
	}