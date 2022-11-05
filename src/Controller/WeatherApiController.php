<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Location;

//use App\Repository\MeasurementRepository;
//use App\Repository\LocationRepository;
//LocationRepository $locationRepository, MeasurementRepository $measurementRepository

use App\Service\WeatherUtil;
use App\Dto\LocationWithMeasurementsDTO;

class WeatherApiController extends AbstractController
{
 
    public function getMeasuresByCityAndCountryPOSTPayloadAction(Request $request, WeatherUtil $weatherUtil): Response
    {

        $payload = json_decode($request->getContent(), true);
        $city = $payload['city'];
        $country = $payload['country'];
        
        $results = $weatherUtil->getWeatherForCountryAndCity($country, $city);

        $data = $this->generateJSON($results["location"], $results["measurements"]);

        $data["query"]["city"] = $city;
        $data["query"]["country"] = $country;

        $response = new Response(json_encode($data, JSON_UNESCAPED_UNICODE), Response::HTTP_OK, ['content-type' => 'application/json']);

        return $response;        
    }

    public function getMeasuresByCityAndCountryJSONAction($countryCode, $cityName, WeatherUtil $weatherUtil): Response
    {
        $results = $weatherUtil->getWeatherForCountryAndCity($countryCode, $cityName);

        $data = $this->generateJSON($results["location"], $results["measurements"]);

        $response = new Response(json_encode($data, JSON_UNESCAPED_UNICODE), Response::HTTP_OK, ['content-type' => 'application/json']);
        
        return $response;
    }

    public function getMeasuresByCityAndCountryCSVAction($countryCode, $cityName, WeatherUtil $weatherUtil): Response
    {
        $results = $weatherUtil->getWeatherForCountryAndCity($countryCode, $cityName);
    
        $csvOutput = $this->generateCSV($results["location"], $results["measurements"]);

        $response = new Response($csvOutput, Response::HTTP_OK);
        
        return $response;
    }

    public function getMeasuresByCityAndCountryJSONTwigAction($countryCode, $cityName, WeatherUtil $weatherUtil): Response
    {
        $results = $weatherUtil->getWeatherForCountryAndCity($countryCode, $cityName);
    
        $data = $this->generateJSON($results["location"], $results["measurements"]);
        
        return $this->render('serialize/serializeJson.json.twig', [
            'data' => $data,
        ]);
        
    }

    public function getMeasuresByCityAndCountryCSVTwigAction($countryCode, $cityName, WeatherUtil $weatherUtil): Response
    {
        $results = $weatherUtil->getWeatherForCountryAndCity($countryCode, $cityName);
        
        return $this->render('serialize/serializeCsv.csv.twig', [
            'loc' => $results["location"],
            'mea' => $results["measurements"]
        ]);
        
    }

    private function generateCSV($location, $measurements) {
        $csvOutput = "location.id,location.city,location.country,location.latitude,location.longitude,measurement.id,measurement.celsius,measurement.fahrenheit,measurement.description,measurement.date\n";

        $locationData = array($location->getId(), $location->getCityName(), $location->getCountryCode(), $location->getLat(), $location->getLon());

        foreach ($measurements as $result) {
            $measurementData = array($result->getId(), $result->getTemperature(), $result->getFahrenheit(), $result->getDescription(), date_format($result->getDate(), 'Y-m-d H:i:s'));

            $tempMerge = array_merge($locationData, $measurementData);
            $csvOutput .= implode(",", $tempMerge);
            $csvOutput .= "\n";

        }
        return $csvOutput;
    }

    private function generateJSON($location, $measurements) {
        $data = array();

        $data["location"]["id"] = $location->getId();
        $data["location"]["city"] = $location->getCityName();
        $data["location"]["country"] = $location->getCountryCode();
        $data["location"]["latitude"] = $location->getLat();
        $data["location"]["longitude"] = $location->getLon();

        foreach ($measurements as $result) {
            $measurement["id"] = $result->getId();
            $measurement["celsius"] = $result->getTemperature();
            $measurement["fahrenheit"] = $result->getFahrenheit();
            $measurement["description"] = $result->getDescription();
            $measurement["date"] = date_format($result->getDate(), 'Y-m-d H:i:s');
            $data["location"]["measurements"][] = $measurement;
        }
        return $data;
    }
    
}
