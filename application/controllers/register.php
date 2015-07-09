<?php
    class Register extends MY_Controller {
        public $layout = "/layouts/_blank";
        public $content = "register/register";
        public $user;
        public $customer;
        public $address;
        public $ui_script = 'fp.ui.loadUI({ui: "reg_form"})';
        
        public function __construct() {
            parent::__construct($this->layout);
            
            $this->load->model('user_model');
            $this->load->model('customer');
            $this->load->model('address');
            
            //setup objects
            $this->user = new User_model();
            $this->customer = new Customer();
            $this->address = new Address();

            $this->load->helper('html');
            $this->load->helper('url');
        }
        
        public function reg_form() {
            $data["main_content"] = $this->content;
            $data["username"] = $this->username;
            $data["ui_script"] = $this->ui_script;
            if(null !== $this->session->userdata('first_name') && $this->session->userdata('first_name') !== json_encode(null)){
                $data['first_name'] = $this->session->userdata('first_name');
            }
            if(null !== $this->session->userdata('last_name') && $this->session->userdata('last_name') !== json_encode(null)){
                $data['last_name'] = $this->session->userdata('last_name');
            }
            if(null !== $this->session->userdata('phone') && $this->session->userdata('phone') !== json_encode(null)){
                $data['phone'] = $this->session->userdata('phone');
            }
            $this->load->view($this->layout, $data);
        }

        public function reg_form_ui(){
            $data["main_content"] = $this->content;
            $data["username"] = $this->username;
            $data["ui_script"] = $this->ui_script;
            if(null !== $this->session->userdata('first_name') && $this->session->userdata('first_name') !== json_encode(null)){
                $data['first_name'] = $this->session->userdata('first_name');
            }
            if(null !== $this->session->userdata('last_name') && $this->session->userdata('last_name') !== json_encode(null)){
                $data['last_name'] = $this->session->userdata('last_name');
            }
            if(null !== $this->session->userdata('phone') && $this->session->userdata('phone') !== json_encode(null)){
                $data['phone'] = $this->session->userdata('phone');
            }
            $this->load->view('partials/_reg_form', $data);
        }
        
        public function user_submit()
        {
            $registrationData = array(
                "username"=>$this->user->username,
                "password"=>$this->user->password,
                "promos"=>$this->user->promos,
                "email"=>$this->user->email,
            );
            
            if($this->user->get_username($registrationData['username'])){
                //Add the user to the database
                if($this->user->add_user($registrationData)){
                	echo USER_REG_PASS;
                }else
                {
                	echo USER_REG_FAIL;
                }
                
                //Obtain the user_id from the user model for population of the customer table
                $user_id = $this->user->get_userId();
                $this->customer->set_userId($user_id);
                $this->customer->add_cust();
                $this->address->add_addr();
                                
                //Update the linking table
                try{
                	if(!$this->user->update_link_table($user_id, $this->customer->cust_id, $this->address->getAddrId()))
                	{
                		redirect("http://www.google.com");
                	}
                }catch(Exception $e)
                {
                	redirect("http://www.google.com");
                }
            }else
            {
                echo REG_USERNAME_USED;
            }
        }
        
        public function get_user($username)
        {
            if($this->user->get_username($username))
            {
                echo trim(REG_USERNAME_OK);
            }else
            {
                echo trim(REG_USERNAME_USED);
            }
        }
    }