<?php

namespace App\Http\Controllers;

use App\APIServices\OpenWeatherAPI;

class WeatherController extends Controller
{
    private $weatherService;

    public function __construct(OpenWeatherAPI $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function getWeather() {
        $cities = [
            'Tokyo',
            'Yokohama',
            'Kyoto',
            'Osaka',
            'Sapporo',
            'Nagoya',
        ];

        $weatherData = [];

        foreach ($cities as $city) {
            $weatherData[$city] = $this->weatherService->getWeatherByCity($city);
        }

        return response()->json($weatherData);
    }
}
