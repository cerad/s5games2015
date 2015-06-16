<?php
namespace Cerad\S5Games;

use Symfony\Component\Console\Command\Command;
//  Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Yaml\Yaml;
use Doctrine\DBAL\Connection;

class TranSincSporGamesCommand extends Command
{
  protected function configure()
  {
    $this
      ->setName('transform')
      ->setDescription('Transform Sinc to Sportacus Games');
  }
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $dataDir = __DIR__ . '/data/';
      
    echo sprintf("Bounce schedules\n");
    
    $loader = new LoadSincGames();
    $sincGames = $loader->load($dataDir . 'SincGames.xlsx');
    file_put_contents($dataDir . 'SincGames.yml',Yaml::dump($sincGames,10,2));
    
    echo sprintf("Loaded %d sinc games\n",count($sincGames));
    
    $loader = new LoadTeamNames();
    $teamNames = $loader->load($dataDir . 'TeamNames.xlsx','TeamNames');
    file_put_contents($dataDir . 'TeamNames.yml',Yaml::dump($teamNames,10,2));
    
    $transformer = new TranSincSporGames();
    $sporGames = $transformer->transform($sincGames,$teamNames);
    
    file_put_contents($dataDir . 'SporGames.yml',Yaml::dump($sporGames,10,2));
    
    $fp = fopen($dataDir . 'SporGames.csv','wt');
    fputcsv($fp,['Date','Time','Division','IDstr','Home','Away','Field','Type','Region']);
    foreach($sporGames as $game) {
      fputcsv($fp,[
        $game['date'],$game['time'],$game['div'],$game['num'],
        $game['teams']['home']['name'],
        $game['teams']['away']['name'],
        $game['field'],$game['type'],'Area5C',
      ]);
    }
    fclose($fp);
  }
}