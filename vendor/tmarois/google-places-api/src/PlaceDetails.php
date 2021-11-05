<?php  namespace GooglePlaces;

class PlaceDetails extends Places
{


    /**
    * $apiPath
    *
    * The Google API URL
    */
    protected $apiPath = 'https://maps.googleapis.com/maps/api/place/details/{FORMAT}';


    /**
    * contructor
    *
    */
    public function __construct(Client $client, $placeId)
    {
        parent::__construct($client);

        $this->setOptions(['placeid' => $placeId, 'language'=> 'de']);
    }


    /**
    * request
    *
    */
    public function request()
    {
        $this->apiPath = str_replace('{FORMAT}','json', $this->apiPath);

        return parent::request();
    }

}
