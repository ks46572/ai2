<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use App\Entity\Location;
use App\Entity\Measurement;

use App\Service\WeatherUtil;

#[AsCommand(
    name: 'LocationCommand',
    description: 'Add a short description for your command',
)]
class LocationCommand extends Command
{

    private $weatherUtil;

    public function __construct(WeatherUtil $weatherUtil) {
        $this->weatherUtil = $weatherUtil;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName("LocationCommand")
            ->setDescription("Displays measurements by id")
            ->addOption('id', null, InputOption::VALUE_REQUIRED, 'Argument description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $idOption = $input->getOption('id');

        if(!$idOption) {
            $io->error("Proivde id");
            return -1;
        }

        $parsedId = ctype_digit($idOption) ? intval($idOption) : null;
        if ($parsedId === null)
        {
            $io->error("Argument must be numeric value");
            return -1;
        }

        $results = $this->weatherUtil->getWeatherForLocationById($parsedId);
       
        if(count($results) == 0) {
            $io->error("Nothing was found");
            return -1;
        }
                
        $data = array();

        foreach ($results as $result) {
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
