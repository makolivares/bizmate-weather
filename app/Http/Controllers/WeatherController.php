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

        return response()->json($this->weatherService->getCurrentWeather($city));
    }

    public function getPlaces(Request $request)
    {
        $city = $request->input('city', 'Tokyo');
        $data = $this->weatherService->getCurrentWeather($city);
        $lat = $data['city']['coord']['lat'];
        $lng = $data['city']['coord']['lon'];

        return response()->json($this->locationService->searchPlaces($lat, $lng));
    }
}
