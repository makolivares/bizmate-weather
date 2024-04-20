<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
    </head>
    <body class="bg-black/50">
        <div id="app" class="flex justify-center pt-32">
            <weather-component :weather-data="weatherData"></weather-component>
        </div>
    </body>

</html>
