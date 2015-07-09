<?php
	class Employees extends MY_Controller
	{
		public $layout = "layouts/_blank";
		public $content = "/employees/main_content";
		public $data;
		private $ui_script = "fp.ui.loadUI({ui: \"employees\"});";

		public function __construct(){
			parent::__construct($this->layout);
			$this->load->helper('form');
			$this->load->helper('url');
			$this->load->helper('html');
			$this->load->library('form_validation');
		}

		public function index($error = null){
			if(isset($error))
			{
				$this->data["error"] = constant($error);
			}
			if($this->elevation == "NORMAL" || $this->elevation == "normal")
			{
				redirect(site_url());
			}else
			{
				$this->data["main_content"] = $this->content;
			}
			$this->data['ui_script'] = $this->ui_script;
			$this->load->view($this->layout, $this->data);
		}

		public function getAllCarriers(){
			echo file_get_contents('./assets/json/cell_carriers.json');
		}

		public function getAllEmployees()
		{
			//Only allow admin users to view results.
			if(!parent::verifyAdmin()){
				redirect(site_url());
			}

			$this->load->library('pagination');

			//Load the model
			$this->load->model('employee_model');

			$employees = $this->employee_model->get();
			$config['base_url'] = "http://localhost/foodport/employees/getAllEmployees";
			$config['total_rows'] = Count($employees);
			$config['per_page'] = 5;
			$config['num_links'] = 20;

			$this->pagination->initialize($config);


			$data['records'] = $this->employee_model->paginationGet($config['per_page'], $this->uri->segment(3));

			$this->load->view('employees/employees_list', $data);
		}

		public function testFunc(){
			$this->load->library('curldriver');
			$restAddress = '1831 Rainier Ave Everett WA 98201';
			$address = "1927 Summitt Ave Everett WA 98201";
			$url = DISTANCE_URL .'json?origins='.urlencode($restAddress).'&destinations='.urlencode($address).'&units=imperial&key='.GOOGLE_APIKEY;

			//Get the distances between the stores and the destination.
			$this->load->library('curldriver');
			$distancesArray = $this->curldriver->curlOpt($url);

			echo '<pre>';
			print_r($distancesArray);
			echo '</pre>';
		}

		public function getEmployee($id)
		{
			//Only allow admin users to view results.
			if(!parent::verifyAdmin()){
				redirect(site_url());
			}

			$this->load->model('employee_model');

			$results = $this->employee_model->get($id);
			
			print_r($results);
		}

		public function create(){
			$this->load->model("employee_model");
			$this->load->model("address");
			$this->load->model("restaurants_model");
			$this->load->model("emp_rest_linkr");

			$address = new Address();
			//Add the address first
			$this->db->trans_start();
			$address->add_addr();

			$Empdata = array(
				'emp_fName' => $this->input->post('first_name'),
				'emp_lName' => $this->input->post('last_name'),
				'addr_id' => $address->getAddrId(),
				'emp_type' => $this->input->post('emp_type'),
				'emp_phone' => $this->input->post('phone'),
				'emp_carrier' => $this->input->post('carrier')
			);

			$empId = $this->employee_model->addEmployee($Empdata);
			
			//Update the restaurant distance table.
			$restaurants = json_decode($this->restaurants_model->getAllRestaurants(), true);
			foreach($restaurants as $restaurant){
				$restAddress = str_replace("<br>", "", $restaurant['address']);

				$url = DISTANCE_URL .'json?origins='.urlencode($restAddress).'&destinations='.urlencode(str_replace("<br>", "", $address->toString())).'&units=imperial&key='.GOOGLE_APIKEY;

				//Get the distances between the stores and the destination.
				$this->load->library('curldriver');
				$distancesArray = $this->curldriver->curlOpt($url);

				$data = [
					'emp_id' => $empId,
					'rest_id' => $restaurant['rest_id'],
					'distance' => $distancesArray['rows'][0]['elements'][0]['distance']['text'],
				];
				$this->emp_rest_linkr->newLink($data);
			}

			$this->db->trans_complete();

			if($this->db->trans_status() === FALSE){
				redirect(base_url('employees/index/EMP_ADD_ERR'));
			}else
			{
				redirect(base_url('employees/index/EMP_ADD_SUCC'));
			}
		}
	}