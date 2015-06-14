<?php
namespace Cerad\S5Games;

use Symfony\Component\Console\Command\Command;
//  Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Yaml\Yaml;
//  Doctrine\DBAL\Connection;

use GuzzleHttp\Client as GuzzleClient;

class BounceCommand extends Command
{
  protected function configure()
  {
    $this
      ->setName('bounce')
      ->setDescription('Bounce Cinc Sportacus');
  }
  protected function execute(InputInterface $input, OutputInterface $output)
  { 
    $dic     = $this->getApplication()->dic;
    $dataDir = $this->getApplication()->dataDir;
    
    $loader   = $dic['api_games_loader'];
    $apiGames = $loader->load();
    
    file_put_contents($dataDir . '/ApiGames.yml', Yaml::dump($apiGames,10,2));
    
    $reporter = $dic['officials_reporter_excel'];
    $reporter->generate($apiGames);
    file_put_contents($dataDir . '/OfficialsReport.xlsx', $reporter->getContents());
    
    return;
  }
}
