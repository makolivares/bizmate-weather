<?php

namespace App\Http\Controllers;

use App\APIServices\FoursquareService;
use App\APIServices\OpenWeatherAPI;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    private $weatherService;
    private $locationService;

    public function __construct(OpenWeatherAPI $weatherService, FoursquareService $locationService)
    {
        $this->weatherService = $weatherService;
        $this->locationService = $locationService;
    }

    public function getWeather(Request $request)
    {
        $city = $request->input('city', 'Tokyo');
        return response()->json([
            'weather' => $this->weatherService->getCurrentWeather($city),
            'location' => $this->locationService->searchPlaces($city)
        ]);
    }
}
