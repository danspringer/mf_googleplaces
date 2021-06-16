# The PHP Google Places API

### NOTE: This project might be missing features, please send pull requests for additional usage.

This package allows you to connect and pull information from the [Google Places API](https://developers.google.com/places/web-service/intro)

You will need a [Google API Client Key](https://developers.google.com/places/web-service/get-api-key) to use Google Places API.

## Features:
* [Place Search](https://developers.google.com/places/web-service/search)
* [Place Details](https://developers.google.com/places/web-service/details)
* [Place Photos](https://developers.google.com/places/web-service/photos) (with local image download)


## Installation:

[Composer](http://getcomposer.org/) to install package.

Use `composer require tmarois/google-places-api dev-master`

## Basic Usage:

```php
$client = new \GooglePlaces\Client('YOUR_CLIENT_KEY');

// find all movie theaters in this zip code
$response = $client->placeSearch('textsearch')->setOptions([
    'query' => 'movie theaters in 28202'
])->request();

// get specific place detail information
$placeId  = 'ChIJvznB1hAnVIgRrWFNVdxDHm0';
$response = $client->placeDetails($placeId)->request();

// get a photo found in the previous responses,
// look up by the "photo_reference"
// save the photo locally for caching
$photoId = 'CmRaAAAAoKx6KQyrDEJ0si1ekan0QaZ6Y02NpXwBFa1ncLaKhZECbFa';
$client->placePhotos($photoId,[450,450])->request()->save(__DIR__.'/images');

```

## Resources:
* [Usage Limits](https://developers.google.com/places/web-service/usage)

## Contributions

Accepting contributions and feedback. Send in any issues and pull requests.
