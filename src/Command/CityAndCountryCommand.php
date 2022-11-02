<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Service\WeatherUtil;


#[AsCommand(
    name: 'CityAndCountryCommand',
    description: 'Add a short description for your command',
)]
class CityAndCountryCommand extends Command
{
    private $weatherUtil;

    public function __construct(WeatherUtil $weatherUtil) {
        $this->weatherUtil = $weatherUtil;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName("CityAndCountryCommand")
            ->setDescription("Displays location by city and country")
            ->addOption('city', null, InputOption::VALUE_REQUIRED, 'City name')
            ->addOption('country', null, InputOption::VALUE_REQUIRED, 'Country name');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $cityOption = $input->getOption('city');
        $countryOption = $input->getOption('country');

        if(!$cityOption) {
            $io->error("Provide city name.");
            return -1;
        }

        if(!$countryOption) {
            $io->error("Provide country name.");
            return -1;
        }
        
        $results = $this->weatherUtil->getWeatherForCountryAndCity($countryOption, $cityOption);
        
        if(count($results) == 0) {
            $io->error("Nothing was found.");
            return -1;
        }
        
        $data = array();

        foreach ($results["measurements"] as $result) {
            $res["id"] = $result->getId();
            $res["temperature"] = $result->getTemperature();
            $res["description"] = $result->getDescription();
            $res["date"] = $result->getDate();
            array_push($data, $res);
        }

        $output->writeln(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        $io->success('Passed successful!');

        return Command::SUCCESS;
    }
}
