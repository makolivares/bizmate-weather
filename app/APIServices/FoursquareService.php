<?php

namespace App\APIServices;

use Exception;
use GuzzleHttp\Client;

class FoursquareService
{

    protected $baseUrl;
    protected $oauth;

    public function __construct()
    {
        $this->baseUrl = 'https://api.foursquare.com/v2/search/recommendations';
        $this->oauth = 'PDCKKUKR11KNZQXLUWJRPHFVAMAEWWGLVDC4A54OPUG2IH1Z';
    }

    public function searchPlaces($latitude, $longitude)
    {
        $apiKey = env('GEOAPIFY_API_KEY'); // Replace with your GeoApify API key
        $radius = 10000; // Search radius in meters (adjust as needed)

        $url = "https://api.geoapify.com/v2/places?categories=commercial&filter=circle:$longitude,$latitude,$radius&apiKey=$apiKey&limit=5";

        $client = new Client();

        try {
            $response = $client->get($url);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                return $data['features'];
            }

            return  $response->getStatusCode();

        } catch (Exception $e) {
            // Handle other exceptions during the request
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
