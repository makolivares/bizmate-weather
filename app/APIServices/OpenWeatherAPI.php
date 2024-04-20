<?php

namespace App\APIServices;

use GuzzleHttp\Client;

class OpenWeatherAPI
{
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getWeatherByCity(string $city): array
    {
        $client = new Client();
        $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$this->apiKey}&units=metric";

        $response = $client->get($url);

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody(), true);
        } else {
            return ['error' => 'Failed to retrieve weather data'];
        }
    }
}
