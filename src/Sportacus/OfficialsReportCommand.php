<?php
namespace Cerad\Component\Sportacus;

use Symfony\Component\Console\Command\Command;
//  Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Yaml\Yaml;

class OfficialsReportCommand extends Command
{
  protected $dic;
  public function __construct($dic)
  {
    parent::__construct();
    $this->dic = $dic;
  }
  protected function configure()
  {
    $this
      ->setName('cerad_sportacus_officials_report')
      ->setDescription('Generate Sportacus Officials Report');
  }
  protected function execute(InputInterface $input, OutputInterface $output)
  { 
    $dataDir = $this->getApplication()->dataDir;
    
    $loader   = $this->dic['cerad_sportacus_games_loader_api'];
    $apiGames = $loader->load();
    
    file_put_contents($dataDir . '/ApiGames.yml', Yaml::dump($apiGames,10,2));
    
    $reporter = $this->dic['cerad_sportacus_officials_reporter_excel'];
    $reporter->generate($apiGames);
    file_put_contents($dataDir . '/OfficialsReport.xlsx', $reporter->getContents());
    
    return;
  }
}
