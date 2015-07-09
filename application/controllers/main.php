<?php
    class Main extends MY_Controller {
        public $layout = 'layouts/_master';
        public $content = '/home/main_content';
        public $debugArr = [];
        
        public function __construct() {
            parent::__construct();
            
            //Included for login
            $this->load->model('user_model');
            $this->load->model('order_model');
            $this->load->model("FdItem_model");
            $this->load->model('alt_prices_model');

            $this->load->library('cart');
        }
        
        public function index($error = '') {
            if($error != ''){
                $data['error'] = $error; 
            }
            $data['content'] = $this->content;
            $data['addr1'] = ($this->session->userdata('addr1')) ? $this->session->userdata('addr1')." ".$this->session->userdata('city')." ".$this->session->userdata('state') : null;
            $data['city'] = ($this->session->userdata('addr1')) ? $this->session->userdata('city') : null;
            $data['zip'] = ($this->session->userdata('addr1')) ? $this->session->userdata('zip') : null;
            //Collect the username from the parent
            $data['username'] = $this->username;
            $this->load->helper('url');
            $this->load->helper('html');
            $this->load->helper('form');
            $this->load->view($this->layout, $data);
        }

        public function getFoodItem($food_id){
            echo json_encode($this->FdItem_model->getFoodItem($food_id));
        }
        
        public function processPromos(){
            //Load the models we'll be using.
            $this->load->model('promos_model');

            //Bring the promos back into an array.
            $promos = $this->input->post('promos');
            $rest_id = $this->input->post('rest_id');
            $food_id = $this->input->post('food_id');
            $food_spec = $this->input->post('food_spec');

            $food = $this->FdItem_model->getFoodItem($food_id);

            //Should only be one item returned so we'll use zero.
            $priceInfo = unserialize($food[0]['price']);

            $promoArr['promos'] = [];
            $promoArr['foods'] = [];


            for($i = 0; $i < count($promos); $i++){
                //This will detect if invalid promos were attempted from the UI.
                //TODO: Return to this later and rectify proper promotions, for now, if they wish to mess
                //  with UI components, the application will not work for them.
                for($j = 0; $j < count($priceInfo['topic']); $j++){
                    if(!in_array($promos[$i], $priceInfo['promos'][$j])){
                        unset($promos[$i]);
                        $i++;
                    }
                }
                $data = [
                    "promo_id" => $promos[$i],
                    "rest_id" => $rest_id
                ];
                //First let's get the promo information
                $tmpArr = $this->promos_model->getPromos($data);
                foreach($tmpArr as $index => $promo){
                    $promoArr['foods'][$i] = [];
                    array_push($promoArr['promos'], $promo);

                    //Collect the promo id
                    $promo_id = $promo['promo_id'];

                    $links = $this->alt_prices_model->getFoodLinks(["promo_id" => $promo_id]);
                    
                    foreach($links as $food){
                        $foodInfo = $this->FdItem_model->getFoodItem($food['food_id']);
                        $actual = [
                            "food_name" => $foodInfo[0]['food_name'],
                            "food_id" => $foodInfo[0]['food_id'],
                            "topics" => unserialize($food['topics']),
                            "discounts" => unserialize($food['discount'])
                        ];
                        $promoArr['foods'][$i][] = $actual;
                    }
                }
            }

            echo json_encode($promoArr);
        }

        public function displayCartItems()
        {
            //Otherwise alert them and add charges if necessary.
            $cartContents = $this->cart->contents();

            if(!empty($cartContents)){
                echo json_encode($cartContents);
            }else{
                echo "Your cart is currently empty...";
            }
        }
        public function updateCart(){
            $cartContents = $this->cart->contents();
            $rowid = $this->input->post("rowid");
            $qty = $this->input->post("qty");
            $operation = $this->input->post('operation');

            $data[] = array(
                'rowid' => $rowid,
                'qty' => $qty
            );

            if($operation == "minus"){
                $count = 0;
                while(!isset($firstItemToBeRemoved) OR $count < count($cartContents)){
                    foreach($cartContents as $item){
                        if($item['rowid'] == $rowid AND $qty < $item['qty']){
                            $firstItemToBeRemoved = $item['id'];
                        }else
                        {
                            if(isset($item['options']['paired']) AND $firstItemToBeRemoved == $item['options']['paired']){
                                $data[] = array(
                                    'rowid' => $item['rowid'],
                                    'qty' => ($item['qty'] - 1)
                                );
                                if($qty == 0){
                                    continue;
                                }else
                                {
                                    break;
                                }
                            }
                        }
                    }
                    $count++;
                }
            }
            $this->order_model->updateCart($data);
        }

        public function addCartItems(){
            $data = [];
            $postArr = json_decode(file_get_contents('php://input'), TRUE);
            //Load in the restaurants model so we can get the restaurant information.
            $this->load->model('restaurants_model');
            //Load the address model so that we can get the restaurant address.
            $this->load->model('address');

            //Array for storing promotions to process after main items have been processed.
            $promosArr = [];

            //First process none promo items.
            foreach($postArr as $orderItem)
            {
                //Lets decode the options so that we get the food specialty
                $options = $orderItem['options'];

                //This to make sure we process promos after everything else, so we can attach
                // rowid's to their options.
                if(isset($options['paired'])){
                    $promosArr[] = $orderItem;
                    continue;
                }

                $this->addItems($orderItem, $options, $data);
            }

            //Then process promo items...
            foreach($promosArr as $key=>$promoItem){
                $options = $promoItem['options'];

                //For some reason empty promos were being sent, took care of this here.
                if(!isset($promoItem['food_id'])){
                    unset($promosArr[$key]);
                    continue;
                }

                $alt_prices = $this->alt_prices_model->getFoodLinks(["food_id"=>$promoItem['food_id']]);
                for($i = 0; $i < count($alt_prices); $i++){
                    $topics = unserialize($alt_prices[$i]['topics']);
                    $discounts = unserialize($alt_prices[$i]['discount']);
                    for($j = 0; $j < count($topics); $j++){
                        if($topics[$j] == $options['food_spec']){
                            $options['discount'] = $discounts[$j];
                        }
                    }
                }

                $this->addItems($promoItem, $options, $data, true);
            }

            $modelRet = $this->order_model->addCartItem($data);

            if($modelRet)
            {
                echo "Success";
                return true;
            }else
            {
                echo "Fail";
                return false;
            }
        }
        public function getNumItems(){
            $counter = 0;
            foreach($this->cart->contents() as $item){
                if(!isset($item['options']['paired'])){
                    $counter += $item['qty'];
                }
            }
            echo $counter;
        }
        private function addItems(&$item, $options, &$data, $promos=false){
            //Collect the restaurant id from the options array.
            $restaurant = $this->restaurants_model->getRestaurant($options['rest_id']);
            //Use the address model's static function to obtain the address
            $restAddress = Address::get_addr($restaurant[0]['addr_id']);

            $addrArr = Array(
                'addr1'=>$restAddress->get_addr1(),
                'addr2'=>$restAddress->get_addr2(),
                'city'=>$restAddress->get_city(),
                'state'=>$restAddress->get_state(),
                'zip'=>$restAddress->get_zip()
            );

            $options['rest_addr'] = $addrArr;

            $price = $this->FdItem_model->getFoodPrice($item['food_id'], $options['food_spec']);

            if($promos){
                $price -= ($price * $options['discount']/100);
            }

            $data[] = array(
                'id' => $item['food_id'],
                'qty' => 1,
                'price' => $price,
                'name' => $item['food_name'],
                'options' => $options
            );
        }

        public function getAllTotals(){
            $this->load->model('order_model');
            echo json_encode($this->order_model->getOrderTotals());
        }

        public function taxRateTest($zip)
        {
            $this->load->model('taxrates');
            $this->taxrates->checkZip($zip);
        }

        public function updateDeliveryAddress(){
            $address = $this->input->post('address');
            $this->session->set_userdata('deliveryAddress', $address);

        }
    }