<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Location;

//use App\Repository\MeasurementRepository;
//use App\Repository\LocationRepository;
//LocationRepository $locationRepository, MeasurementRepository $measurementRepository

use App\Service\WeatherUtil;

class WeatherController extends AbstractController
{
    public function cityAction($countryCode, $cityName, WeatherUtil $weatherUtil): Response
    {
        $result = $weatherUtil->getWeatherForCountryAndCity($countryCode, $cityName);

        return $this->render('weather/city.html.twig', [
        'location' => $result["location"],
        'measurements' => $result["measurements"],
        ]);
    }
}
