<?php

namespace App\APIServices;

use GuzzleHttp\Client;

class OpenWeatherAPI
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = 'https://api.openweathermap.org/data/2.5/forecast';
    }

    public function getCurrentWeather(string $city)
    {
        $client = new Client();

        $response = $client->get($this->baseUrl, [
            'query' => [
                'q' => $city,
                'units' => 'metric',
                'cnt' => 7,
                'appid' => $this->apiKey

            ]
        ]);

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody(), true);
        }

        return ['error' => 'Failed to retrieve weather data'];

    }

}
