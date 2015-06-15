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
use Pimple\Container as Dic;

class BounceCommand extends Command
{
  protected $dic;
  protected $dataDir;
  
  public function __construct($dataDir, Dic $dic)
  {
    parent::__construct();
    
    $this->dic = $dic;
    $this->dataDir = $dataDir;
  }
  protected function configure()
  {
    $this
      ->setName('bounce')
      ->setDescription('Bounce Cinc Sportacus');
  }
  protected function execute(InputInterface $input, OutputInterface $output)
  { 
    $dataDir = $this->dataDir;
    
    $loader    = $this->dic['cerad_sinc_games_loader_excel'];
    $sincGames = $loader->load($dataDir . '/SincGames.xlsx');
    
    file_put_contents($dataDir . '/SincGames.yml', Yaml::dump($sincGames,10,2));
    
    echo sprintf("Loaded %d sinc games\n",count($sincGames));
    
    return;
    
    $loader   = $this->dic['cerad_sportacus_games_loader_api'];
    $apiGames = $loader->load();
    
    file_put_contents($dataDir . '/ApiGames.yml', Yaml::dump($apiGames,10,2));
    
    $reporter = $this->dic['cerad_sportacus_officials_reporter_excel'];
    $reporter->generate($apiGames);
    file_put_contents($dataDir . '/OfficialsReport.xlsx', $reporter->getContents());
    
    return;
  }
}
