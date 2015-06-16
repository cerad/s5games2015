<?php
namespace Cerad\Component\Sportacus;

use Symfony\Component\Console\Command\Command;
//  Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Yaml\Yaml;
//  Doctrine\DBAL\Connection;

use Interop\Container\ContainerInterface as DicInterface;

class GamesCompareSincCommand extends Command
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
      ->setName('cerad_sportacus_games_compare_sinc')
      ->setDescription('Games Compare Cinc');
  }
  protected function execute(InputInterface $input, OutputInterface $output)
  { 
    $dataDir = $this->dataDir;
    
    $loader = $this->dic->get('cerad_sinc_games_loader_excel');
    $sincGames = $loader->load($dataDir . '/SincGames.xlsx');
  //sort($sincGames);
    file_put_contents($dataDir . '/SincGames.yml', Yaml::dump($sincGames,10,2));
    
    echo sprintf("Loaded %d sinc games\n",count($sincGames));
    
    $loader = $this->dic->get('cerad_sportacus_games_loader_api');
    $sportacusGames = $loader->load();
  //sort($sportacusGames);
    file_put_contents($dataDir . '/SportacusGames.yml', Yaml::dump($sportacusGames,10,2));
    
    echo sprintf("Loaded %d sportacus games\n",count($sportacusGames));
    
    $comparer = $this->dic->get('cerad_sportacus_games_comparer_sinc');
    $results  = $comparer->compare($sportacusGames,$sincGames);
    print_r($results);
    return;
  }
}
