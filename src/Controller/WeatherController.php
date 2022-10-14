<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Location;
use App\Repository\MeasurementRepository;
use App\Repository\LocationRepository;


class WeatherController extends AbstractController
{
    public function cityAction($countryCode, $cityName, LocationRepository $locationRepository, MeasurementRepository $measurementRepository): Response
    {
        $location = $locationRepository->findOneByCountryCodeAndCity($countryCode, $cityName);
        $measurements = $measurementRepository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
        'location' => $location,
        'measurements' => $measurements,
        ]);
    }
}
