 <?php
    Class Address extends CRUD_model{

        protected $_table = "addresses";
        protected $_primary_key = "address_id";

        private $addr1;
        private $apt;
        private $addr2;
        private $city;
        private $state;
        private $zip;
        
        private $address_id;
        
        public function __construct() {
            parent::__construct();
            
            $this->addr1 = $this->input->post('addr1');
            $this->addr2 = $this->input->post('addr2');
            $this->apt = $this->input->post('apt');
            $this->city = $this->input->post('city');
            $this->state = $this->input->post('state');
            $this->zip = $this->input->post('zip');
            
            //TODO: possible transaction conflict.
            $this->address_id = $this->getAddrId();
        }
        
        public function add_addr(){
            $addressData = array(
                "addr1" => $this->addr1,
                "apt" => $this->apt,
                "addr2" => $this->addr2,
                "city" => $this->city,
                "state" => $this->state,
                "zip" => $this->zip,
            );
            $this->load->model('taxrates');
            $this->taxrates->checkZip($this->zip);
            if($this->db->insert('addresses', $addressData)){
            	$this->address_id = $this->db->insert_id();
            	return $this->address_id;	
            }else {
            	return false;
            }
            
        }
        
        public function getAddrId() {
        	if(isset($this->address_id)){
        		return $this->address_id;
        	}else {
        		return false;
        	}
        }
        
        public function get_addr1() {
        	return $this->addr1;
        }
        
        public function set_addr1($addr1)
        {
        	$this->addr1 = $addr1;
        }
        public function get_apt(){
            return $this->apt;
        }
        public function set_apt($apt)
        {
        	$this->apt = $apt;
        }
        public function get_addr2(){
            return $this->addr2;
        }
        public function set_addr2($addr2)
        {
        	$this->addr2 = $addr2;
        }
        
        public function get_city(){
            return $this->city;
        }
        
        public function set_city($city)
        {
        	$this->city = $city;
        }
        
        public function get_state(){
            return $this->state;
        }
        public function set_state($state)
        {
        	$this->state = $state;
        }

        public function get_zip()
        {
            return $this->zip;
        }
        
        public function set_zip($zip)
        {
        	$this->zip = $zip;
        }
        
        //Public API get_addr
        public static function get_addr($addr_id) {
        	
        	$CI = &get_instance();
        	
        	$query = $CI->db->get_where('addresses', array('address_id'=>$addr_id));
        	
        	$tempAddress = new Address();
        	        	
        	foreach($query->result() as $addr) {
        		$tempAddress->set_addr1($addr->addr1);
        		$tempAddress->set_apt($addr->apt);
        		$tempAddress->set_addr2($addr->addr2);
        		$tempAddress->set_city($addr->city);
        		$tempAddress->set_state($addr->state);
        		$tempAddress->set_zip($addr->zip);
        	}
        	        	
        	return $tempAddress;
        }
        
        public function toString()
        {
        	$retString = $this->addr1 . " ";
        	if($this->apt !== "0"){$retString .= $this->apt;}
        	if(!empty($this->addr2))
        	{
        		$retString .= "<br>" . $this->addr2;
        	}else
        	{
        		$retString .= "<br>" . $this->city . ", " . $this->state . " " . $this->zip;
        	}
        	
        	return $retString;
        }

        public function updateAddr($addrId){
            $addrData = [
                "addr1" => $this->addr1,
                "apt" => $this->apt,
                "addr2" => $this->addr2,
                "city" => $this->city,
                "state" => $this->state,
                "zip" => $this->zip
            ];

            parent::update($addrData, $addrId);
        }
    }