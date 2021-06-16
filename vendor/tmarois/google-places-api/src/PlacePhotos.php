<?php  namespace GooglePlaces;

class PlacePhotos extends Places
{

    /**
    * $apiPath
    *
    * The Google API URL
    */
    protected $apiPath = 'https://maps.googleapis.com/maps/api/place/photo';


    /**
    * $photoId
    *
    * The Google API $photoId
    */
    protected $photoId;


    /**
    * $image
    *
    * The Google API Image Content
    */
    protected $image;


    /**
    * $apiFormat
    *
    * The Google API format
    */
    protected $apiFormat = 'image';


    /**
    * contructor
    *
    */
    public function __construct(Client $client, string $photoId, array $size = [])
    {
        parent::__construct($client);

        $this->photoId = $photoId;

        $this->setOptions([
            'photoreference' => $this->photoId,
            'maxheight' => ($size[0]) ?? 1600,
            'maxwidth'  => ($size[1]) ?? 1600
        ]);
    }


    /**
    * response
    *
    */
    public function response($output)
    {
        $this->image = $output;

        return $this;
    }


    /**
    * getRawImage
    *
    */
    public function getRawImage()
    {
        return $this->image;
    }


    /**
    * save
    *
    */
    public function save(string $location)
    {
        return file_put_contents($location.'/'.$this->photoId.'.jpg', $this->getRawImage());
    }

}
