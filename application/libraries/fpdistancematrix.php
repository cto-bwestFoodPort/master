<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class FpDistanceMatrix {


	public function findFurthestDistance($distanceArray){
		$distances = (array) new stdClass();
		$distances = $distanceArray;

		$length = count($distances['destination_addresses']);

		$retVar = 0;
		$flag = null;
		$distance = null;

		foreach($distances['rows'][0]['elements'] as $key=>$element){
			$distance = (double) str_replace(",", "",$element['distance']['text']);

			//So long as the distance is less than 15.0 miles, return the furthest distance of the restaurants from the delivery address.
			$retVar = ($distance > $retVar && $distance <= 15.0) ? $distance : $retVar;

			if($distance > 15.0)
			{
				$flag['keys'][] = $key;
			}
		}

		if(is_array($flag)){
			return [
				"distance"=>$retVar,
				"remove" => $flag['keys']
			];
		}else
		{
			return $retVar;
		}
	}
}