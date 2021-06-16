<?php  namespace GooglePlaces;

class Places
{

    /**
    * $client
    *
    * @see \GooglePlaces\Client
    */
    protected $client;


    /**
    * $apiPath
    *
    * The Google API URL
    */
    protected $apiPath = '';


    /**
    * $apiFormat
    *
    * The Google API format
    */
    protected $apiFormat = 'json';


    /**
    * $apiOptions
    *
    * The Google API Paramters to pass
    */
    protected $apiOptions = [];


    /**
    * contructor
    *
    */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    /**
    * client
    *
    */
    public function client()
    {
        return $this->client;
    }


    /**
    * getPath
    *
    */
    public function getOptions()
    {
        return $this->apiOptions;
    }


    /**
    * getPath
    *
    */
    public function getPath()
    {
        return $this->apiPath;
    }


    /**
    * getFormat
    *
    */
    public function getFormat()
    {
        return $this->apiFormat;
    }


    /**
    * options
    *
    */
    public function setOptions(array $options = [])
    {
        $this->apiOptions = array_merge($options, $this->apiOptions);

        return $this;
    }


    /**
    * response
    *
    */
    public function response($output)
    {
        return ($output) ?? null;
    }


    /**
    * request
    *
    */
    public function request()
    {
        return (new Request($this))->output();
    }

}
