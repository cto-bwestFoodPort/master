<?php
	class TaxRates extends CRUD_model
	{
		protected $_table = "tax_rates";
		protected $_primary_key = "zip";

		public function checkZip($zip){
			$result = parent::get($zip);

			if(count($result) == 0)
			{
				$this->getZipRate($zip);
			}
		}

		private function getZipRate($zip){

			$options = Array(
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_FOLLOWLOCATION => TRUE,
				CURLOPT_CONNECTTIMEOUT => 120,
				CURLOPT_TIMEOUT => 120,
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
				CURLOPT_URL => TAX_URL . "?country=usa&postal=".$zip."&apikey=".TAX_APIKEY,
				CURLOPT_SSL_VERIFYPEER => FALSE
			);

			$ch = curl_init();
			curl_setopt_array($ch, $options);

			if($data = curl_exec($ch))
			{
				echo "passed";
			}else
			{
				echo curl_error($ch);
			}
			curl_close($ch);
			$results = json_decode($data, true);

			$this->storeZip($zip, $results['totalRate']);
		}

		private function storeZip($zip, $rate)
		{
			$data = Array(
				"zip" => $zip,
				"tax_rate" => $rate
			);
			try{
				parent::insert($data);
			}
			catch(Exception $ex)
			{
				show_error($ex->getMessage());
			}
		}

		public function getDBTaxRate($zip)
		{
			$results = parent::get(["zip" => $zip]);
			return $results[0]['tax_rate'];
		}
	}