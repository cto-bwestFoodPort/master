<?php

class News extends MY_Controller {
	
	public $layout = 'layouts/_blank';
	public $content = '/news/create';
	private $ui_script = "fp.ui.loadUI({ui: \"news\"});";
	
	public function __construct() {
		parent::__construct($this->layout);
		$this->load->helper('url');
		$this->load->helper('html');
		$this->load->model('news_model');
	}
	
	public function index() {
		redirect('/news/create/');
	}
	
	public function create()
	{
		if($this->elevation == "NORMAL" || $this->elevation == "normal")
		{
			redirect(site_url());
		}else
		{
			$this->data["main_content"] = $this->content;
		}
		
		$data['ui_script'] = $this->ui_script;
		$message = "News item queued for approval.";
		
		$data['username'] = $this->username;
		$data['main_content'] = $this->content;
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		
		$data['title'] = 'Create a news item';
	
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('text', 'News Content', 'required');
	
		if ($this->form_validation->run() === TRUE)
		{
			$this->news_model->set_news();
		}
		
		$this->load->view($this->layout, $data);
	}
	
}
