<?php  namespace GooglePlaces;

class PlaceSearch extends Places
{

    /**
    * $apiPath
    *
    * The Google API URL
    */
    protected $apiPath = 'https://maps.googleapis.com/maps/api/place/{REQUEST}/{FORMAT}';


    /**
    * $apiAllowedRequest
    *
    * The Google API Allowed requests
    */
    protected $apiAllowedRequest = ['textsearch','nearbysearch'];


    /**
    * $apiRequest
    *
    * The Google API request type
    */
    protected $apiRequest;


    /**
    * contructor
    *
    */
    public function __construct(Client $client, $request)
    {
        parent::__construct($client);

        $this->apiRequest = $request;
    }


    /**
    * response
    *
    */
    public function response($output = [])
    {
        return ($output['results']) ?? [];
    }


    /**
    * request
    *
    */
    public function request()
    {
        $this->apiPath = str_replace('{REQUEST}',$this->apiRequest, $this->apiPath);
        $this->apiPath = str_replace('{FORMAT}','json', $this->apiPath);

        return parent::request();
    }

}
