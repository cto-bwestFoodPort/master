<?php
	class Checkout extends MY_Controller
	{
		public $layout = "layouts/_blank";
		public $content = "checkout/main_content";
		public $ui_script = "fp.ui.loadUI({ui: \"checkout\"})";
		protected $data = null;
		public $totals = null;

		public function __construct(){
			parent::__construct();
			$this->load->helper('url');
			$this->load->helper('html');
			$this->load->helper('form');

			$this->load->library('cart');

			$this->load->model('order_model');
		}

		public function index(){
			//Get the totals for checkout display.
			$totals = $this->order_model->getOrderTotals();
			$this->data['main_content'] = $this->content;
			$this->data['ui_script'] = $this->ui_script;
			$this->data['cart_contents'] = $this->cart->contents();

			if($this->username == "Guest"){
				$address = "No address has been specified, please enter one before continuing.";
			}else
			{
				$address = "<span id='name'>".$this->session->userdata('first_name')." ".$this->session->userdata('last_name')."</span><br>";
				$address .= "<span id='name'>".$this->session->userdata('addr1') . "<br>";
				if(null !== $this->session->userdata('addr2') && $this->session->userdata('addr2') !== ""){
					$address .= "<span id='name'>".$this->session->userdata('addr2') . "</span> ";
				}
				if(null !== $this->session->userdata('apt') && $this->session->userdata('apt') !== ""){
					$address .= "<span id='name'>".$this->session->userdata('apt') . "</span><br>";
				}
				$address .= "<span id='addr3'>".$this->session->userdata('city') . " ";
				$address .= $this->session->userdata('state') . ", ";
				$address .= "<span id='zip'>".$this->session->userdata('zip') . "</span>";
			}

			$this->data['totals'] = json_encode($totals);
			$this->data['address'] = $address;


			$this->data['first_name'] = $this->session->userdata('first_name');
            $this->data['last_name'] = $this->session->userdata('last_name');
            $this->data['phone'] = $this->session->userdata('phone');

			$this->load->view($this->layout, $this->data);
		}

		public function change_form() {

            if(null !== $this->session->userdata('first_name') && $this->session->userdata('first_name') !== json_encode(null)){
                $data['first_name'] = $this->session->userdata('first_name');
            }
            if(null !== $this->session->userdata('last_name') && $this->session->userdata('last_name') !== json_encode(null)){
                $data['last_name'] = $this->session->userdata('last_name');
            }
            if(null !== $this->session->userdata('phone') && $this->session->userdata('phone') !== json_encode(null)){
                $data['phone'] = $this->session->userdata('phone');
            }
            $this->load->view('checkout/info_change_form', $data);
        }
	}