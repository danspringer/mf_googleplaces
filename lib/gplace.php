<?php

class gplace {
	

    // Deklaration einer Methode
    function get($qry = "") {
		
		$place = new gplace;
		$place->apiKey = rex_addon::get('mf_googleplaces')->getConfig('gmaps-api-key');
		$place->placeId = rex_addon::get('mf_googleplaces')->getConfig('gmaps-location-id');
		
		$client = new \GooglePlaces\Client($place->apiKey);
		$response = $client->placeDetails($place->placeId)->request();
        if($qry == "") {
			return $response;
			}
		else {
			return $response['result'][$qry];
			}
		
    }
	
		// find all movie theaters in this zip code
		/*$response = $client->placeSearch('textsearch')->setOptions([
			'query' => 'latin groove tanzschule'
		])->request();
		*/
		// get specific place detail information
		//$response = $client->placeDetails($googlePlaceId)->request();

}
?>