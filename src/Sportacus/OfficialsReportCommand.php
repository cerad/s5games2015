<?php
namespace Cerad\Component\Sportacus;

use Symfony\Component\Console\Command\Command;
//  Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Yaml\Yaml;

use Interop\Container\ContainerInterface as DicInterface;

class OfficialsReportCommand extends Command
{
  protected $dic;
  protected $dataDir;
  
  public function __construct($dataDir, DicInterface $dic)
  {
    parent::__construct();
    
    $this->dic     = $dic;
    $this->dataDir = $dataDir;
  }
  protected function configure()
  {
    $this
      ->setName('cerad_sportacus_officials_report')
      ->setDescription('Generate Sportacus Officials Report');
  }
  protected function execute(InputInterface $input, OutputInterface $output)
  { 
    $dataDir = $this->dataDir;
    
    $loader   = $this->dic->get('cerad_sportacus_games_loader_api');
    $apiGames = $loader->load();
    
    file_put_contents($dataDir . '/ApiGames.yml', Yaml::dump($apiGames,10,2));
    
    $reporter = $this->dic->get('cerad_sportacus_officials_reporter_excel');
    $reporter->generate($apiGames);
    
    file_put_contents($dataDir . '/OfficialsReport.xlsx', $reporter->getContents());
  }
}
