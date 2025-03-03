<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WeatherService
{
    private $apiKey;
    private $baseUrl = 'https://api.openweathermap.org/data/2.5/weather';

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->apiKey = config('services.openweathermap.key');
    }

    public function getWeatherForCity(string $city): array
    {
        try {
            $response = Http::get($this->baseUrl, [
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => 'metric'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'temperature' => $data['main']['temp'],
                    'condition' => $data['weather'][0]['description']
                ];
            }

            return [
                'temperature' => null,
                'condition' => 'Unable to fetch weather data'
            ];
        } catch (\Exception $e) {
            return [
                'temperature' => null,
                'condition' => 'Error fetching weather data'
            ];
        }
    }
}
