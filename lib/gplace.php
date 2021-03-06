<?php

/**
 * Google Places AddOn: Gibt Details zu einem Google Place aus.
 *
 * @package redaxo\mf_googleplaces
 */
class gplace
{
    /**
     * Bindet die Vendors ein - als Funktion, weil es sonst in der boot.php immer geladen werden müsste und das ist nicht nötig
     * @author Daniel Springer
     */
    public static function includeVendors()
    {
        include_once rex_path::addon('mf_googleplaces', 'vendor/guzzlehttp/guzzle/src/functions.php');
        include_once rex_path::addon('mf_googleplaces', 'vendor/guzzlehttp/psr7/src/functions.php');
        include_once rex_path::addon('mf_googleplaces', 'vendor/guzzlehttp/promises/src/functions.php');

    } // EoF

    /**
     * Ruft Details zu einem Google Place ab.
     * @param string $qry
     * @return array | string
     * @author Daniel Springer
     */
    public static function get(string $qry = "")
    {
        gplace::includeVendors();
        $place = new gplace;
        $place->apiKey = rex_addon::get('mf_googleplaces')->getConfig('gmaps-api-key');
        $place->placeId = rex_addon::get('mf_googleplaces')->getConfig('gmaps-location-id');

        $client = new \GooglePlaces\Client($place->apiKey);
        $response = $client->placeDetails($place->placeId)->request();
        if ($qry == "") {
            return $response['result'];
        } else {
            return $response['result'][$qry];
        }

    } // EoF

    /**
     * Ruft Details zu einem Google Place direkt über die Google API ab.
     * @param string $qry
     * @return array | string
     * @author Daniel Springer
     */
    public static function getFromGoogle(string $qry = "")
    {
        gplace::includeVendors();
        $place = new gplace;
        $place->apiKey = rex_addon::get('mf_googleplaces')->getConfig('gmaps-api-key');
        $place->placeId = rex_addon::get('mf_googleplaces')->getConfig('gmaps-location-id');

        $client = new \GooglePlaces\Client($place->apiKey);
        $response = $client->placeDetails($place->placeId)->request('DE');
        if ($qry == "") {
            return $response;
        } else {
            return $response['result'][$qry];
        }

    } // EoF

    /**
     * Ruft Reviews zu einem Google Place direkt über die Google API ab (wsl. limitiert auf die letzten 5).
     * @return array
     * @author Daniel Springer
     */
    public static function getAllReviewsFromGoogle()
    {
        gplace::includeVendors();
        $qry = 'reviews';
        $place = new gplace;
        $place->apiKey = rex_addon::get('mf_googleplaces')->getConfig('gmaps-api-key');
        $place->placeId = rex_addon::get('mf_googleplaces')->getConfig('gmaps-location-id');

        $client = new \GooglePlaces\Client($place->apiKey);
        $response = $client->placeDetails($place->placeId)->request();

        return $response['result'][$qry];
    } // EoF

    /**
     * Ruft alle Reviews zu einem Google Place aus der eigenen DB ab.
     * @return array
     * @author Daniel Springer
     */
    public static function getAllReviews(string $orderBy = "", int $limit = NULL)
    {
        $sql = rex_sql::factory();
        $qry = 'SELECT * FROM mf_googleplaces_reviews';

        if($orderBy != ""){
            $qry .= ' ORDER BY '.$orderBy;
        }
        if($limit != ""){
            $qry .= ' LIMIT '.$limit;
        }
        $sql->setQuery($qry);

        $response = [];
        foreach ($sql as $row) {
            $id = $row->getValue('id');
            $response[$id]['id'] = $row->getValue('id');
            $response[$id]['author_name'] = $row->getValue('author_name');
            $response[$id]['author_url'] = $row->getValue('author_url');
            $response[$id]['language'] = $row->getValue('language');
            $response[$id]['profile_photo_url'] = $row->getValue('profile_photo_url');
            $response[$id]['rating'] = $row->getValue('rating');
            $response[$id]['text'] = $row->getValue('text');
            $response[$id]['profile_photo_url'] = $row->getValue('profile_photo_url');
            $response[$id]['time'] = $row->getValue('time');
            $response[$id]['profile_photo_url'] = $row->getValue('profile_photo_url');
            $response[$id]['createdate_addon'] = $row->getValue('createdate_addon');
            $response[$id]['google_place_id'] = $row->getValue('google_place_id');
        }
        return $response;
    } // EoF

    /**
     * Ruft die durschnittliche Bewertung aller Reviews zu einem Google Place aus der eigenen DB ab.
     * @return float
     * @author Daniel Springer
     */
    public static function getAvgRating()
    {
        $sql = rex_sql::factory();
        $sql->setQuery('SELECT rating FROM mf_googleplaces_reviews');
        $rating = 0;
        $i = $sql->getRows();
        foreach ($sql as $row) {
           $rating = $rating + $row->getValue('rating');
        }
        return round(floatval($rating/$i),1);
    } // EoF

    /**
     * Ruft die Anzahl aller Reviews zu einem Google Place aus der eigenen DB ab.
     * @return int
     * @author Daniel Springer
     */
    public static function getTotalRatings()
    {
        $sql = rex_sql::factory();
        $sql->setQuery('SELECT * FROM mf_googleplaces_reviews');
        $i = $sql->getRows();
        return $i;
    } // EoF

    /**
     * Holt die Reviews von der Google API und speichert sie in der DB. Wenn der Eintrag bereits vorhanden ist, wird
     * er nicht verändert.
     * @return bool
     * @author Daniel Springer
     */
    public static function updateReviewsDB()
    {
        gplace::includeVendors();
        $googleReviews = gplace::getAllReviewsFromGoogle();
        $googlePlaceId = gplace::get('place_id');

        foreach ($googleReviews as $gr) {
            $googleTime = (string)$gr['time'];
            #dump($googleTime);

            $sql_search = rex_sql::factory();
            $sql_search->setDebug(false);
            $sql_search->setQuery('SELECT * FROM mf_googleplaces_reviews WHERE time = :time', ['time' => $googleTime]);
            #dump($sql_search->getRows());

            if ($sql_search->getRows() == 0) {
                // Wenn Review noch nicht existiert, in DB schreiben
                $objDateTime = new DateTime('NOW');
                $dateTime = $objDateTime->format('Y-m-d H:i:s');
                $sql = rex_sql::factory();
                $sql->setDebug(false);
                $sql->setTable('mf_googleplaces_reviews');
                $sql->setValues(
                    [
                        'author_name' => $gr['author_name'],
                        'author_url' => $gr['author_url'],
                        'language' => $gr['language'],
                        'profile_photo_url' => $gr['profile_photo_url'],
                        'rating' => $gr['rating'],
                        'text' => $gr['text'],
                        'profile_photo_url' => $gr['profile_photo_url'],
                        'time' => $gr['time'],
                        'profile_photo_url' => $gr['profile_photo_url'],
                        'google_place_id' => $googlePlaceId,
                        'createdate_addon' => $dateTime
                    ]
                );
                $sql->insert();
            } else {
                #echo 'Review existiert in DB bereits';
            }
        }

        return true;
    } // EoF

} // EoC

?>