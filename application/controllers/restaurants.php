<?php
	class Restaurants extends MY_Controller {

		/**
		* Query that I need:
		* SELECT fc.name, fi.* 
		* FROM food_cat as fc, food_items as fi, restaurants as rest 
		* WHERE fc.rest_id = rest.rest_id AND fi.fdcat_id = fc.fdcat_id AND rest.rest_id = 1
		**/
		public $layout = "layouts/_blank";
		public $content = "restaurants/main_content";
		private $restaurants;
		public $address;
		private $rest_categories;
		private $restCollections;
		
		private $ui_script = "fp.ui.loadUI({ui: \"restaurants\"});";
		
		public function __construct() {
			parent::__construct($this->layout);
			$this->load->helper(array('url', 'html', 'form'));
			//Load the models.
			$this->load->model('restcat_model');
			$this->load->model('restaurants_model');
			$this->load->model('address');
			
			$this->load->library('form_validation');
		}
		
		public function index() {
			if(!parent::verifyAdmin())
			{
				redirect(site_url());
			}else
			{
				$data["main_content"] = $this->content;
			}

			$data["username"] = $this->username;
			$data["ui_script"] = $this->ui_script;

			//Collect the restaurant categories
			$this->rest_categories = new Restcat_model();
			$tmpCatArray = $this->rest_categories->getCatNameArray();
			$tmpCatIdArray = $this->rest_categories->getCatIdArray();
			foreach($tmpCatArray as $category){
				$data['categories'][] = $category;
			}
			foreach($tmpCatIdArray as $cat_id){
				$data['cat_ids'][] = $cat_id;
			}
			
			$this->form_validation->set_rules('rest_name', 'Restaurant Name', 'required');
			$this->form_validation->set_rules('addr1', 'Address 1', 'required');
			$this->form_validation->set_rules('city', 'City', 'required');
			$this->form_validation->set_rules('state', 'State', 'required');
			$this->form_validation->set_rules('zip', 'Zip Code', 'required');
			
			if($this->form_validation->run() === TRUE){
				//Initialize an address object
				$this->address = new Address();
				
				//Add the address to the addresses table.
				$this->address->add_addr();

				$query = $this->restaurants_model->add_restaurant($this->address->getAddrId());

				if($query !== FALSE){
					$data['form_submit'] = "Restaurant Successfully added.";

					//Update the restaurant distances table.
					$this->load->model('employee_model');
					$employees = $this->employee_model->getAllEmployees();

					foreach($employees as $employee){
						$empAddr = Address::get_addr($employee['addr_id']);

						$url = DISTANCE_URL .'json?origins='.urlencode(str_replace("<br>", "", $empAddr->toString())).'&destinations='.urlencode(str_replace("<br>", "", $this->address->toString())).'&units=imperial&key='.GOOGLE_APIKEY;

						//Get the distances between the stores and the destination.
						$this->load->library('curldriver');
						$distancesArray = $this->curldriver->curlOpt($url);

						$data = [
							'emp_id' => $employee['emp_id'],
							'rest_id' => $query,
							'distance' => $distancesArray['rows'][0]['elements'][0]['distance']['text'],
						];
						$this->load->model('emp_rest_linkr');
						$this->emp_rest_linkr->newLink($data);
					}
				}
			}else
			{
				$data['form_submit'] = "Restaurant add failed.";
			}
			$this->load->view($this->layout, $data);
		}
		
		//GET Api
		//restaurants/getAllRestaurants
		public function getAllRestaurants(){
			echo str_replace("\\", "", trim($this->restaurants_model->getAllRestaurants()));
		}

		public function getRestaurant($id){
			//TODO: implement httpRequest checking later.
			//In case this is called via ajax.
			echo json_encode($this->restaurants_model->getRestaurant($id));
			return $this->restaurants_model->getRestaurant($id);
		}

		/**
		* API: getFoodCategories
		* @usage: restaurants/getFoodCategories/1
		**/
		public function getFoodCategories($rest_id){
			$categories = $this->restaurants_model->getFoodCategories($rest_id);
			echo json_encode($categories);
		}

		public function getPromos(){
			if(parent::verifyAdmin()){
				$data = [
					"rest_id" => $this->input->post('rest_id')
				];
				$this->load->model('promos_model');
				echo json_encode($this->promos_model->getPromos($data));
			}	
		}

		public function loadComboPage(){
			$rest_name = str_replace([" ", "'","_&_"], ["_", "", "_"], strtolower($this->input->post('rest_name')));
			$rest_id = $this->input->post('rest_id');

			$table = $rest_name . "_" . $rest_id;

			$results = $this->restaurants_model->getMenuItems($table);

			$catArray = [];

			//Get the categories first with their respected foods into an array.
			foreach($results as $item){
				$catArray[$item['name']][] = [
					"food_id" => $item['food_id'],
					"food_name" => $item['food_name'],
					"fdcat_id" => $item['fdcat_id'],
					"description" => $item['description'],
					"price" => $item['price']
				];
			}

			$data = [
				"rest_name" => $rest_name,
				"rest_id" => $rest_id,
				"food_items" => $catArray
			];

			$this->load->view('restaurants/add_rules', $data);
		}

		public function addPromoRules(){
			if(parent::verifyAdmin()){
				$this->load->model("alt_prices_model");
				$this->alt_prices_model->addPromoAndPrices();
			}
		}

		public function getItemTable(){
			$rest_name = str_replace([" ", "'","_&_"], ["_", "", "_"], strtolower($this->input->post('rest_name')));
			$rest_id = $this->input->post('rest_id');

			$table = $rest_name . "_" . $rest_id;

			$results = $this->restaurants_model->getMenuItems($table);

			$data = [
				"rest_name" => $rest_name,
				"rest_id" => $rest_id,
				"food_items" => $results
			];

			$this->load->view('restaurants/edit_item', $data);
		}

		/**
		* API: getMenuItems
		* POST params: "rest_name", rest_id
		**/
		public function getMenuItems()
		{
			$rest_name = str_replace([" ", "'","_&_"], ["_", "", "_"], strtolower($this->input->post('rest_name')));
			$rest_id = $this->input->post('rest_id');

			$table = $rest_name . "_" . $rest_id;

			echo json_encode($this->restaurants_model->getMenuItems($table));
		}

		public function addFoodCategory(){
			if(parent::verifyAdmin()){
				$catData = array(
					"rest_id" => $this->input->post('rest_id'),
					"name" => $this->input->post('category'),
					"notes" => $this->input->post('cat_notes')
				);

				if($this->restaurants_model->addFoodCategory($catData))
				{
					echo "Success";
				}
			}
		}

		public function addFoodItem(){
			if(parent::verifyAdmin()){
				$promos = (null !== $this->input->post('promos'))?serialize($this->input->post('promos')) : null;
				$foodData = array(
					"food_name" => $this->input->post('food_name'),
					"fdcat_id" => $this->input->post('fdCat'),
					"description" => $this->input->post('description'),
					"price" => serialize($this->input->post('price')),
					"limited" => 0,
					"keywords" => $this->input->post('keywords')
				);

				if($this->restaurants_model->addFoodItem($foodData))
				{
					echo "Success";
				}
			}
		}
	}