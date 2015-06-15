<?php
namespace Cerad\Component\Sinc;

use Symfony\Component\Console\Command\Command;
//  Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Yaml\Yaml;
//  Doctrine\DBAL\Connection;

class TeamsRefereesCommand extends Command
{
  protected function configure()
  {
    $this
      ->setName('cerad_sinc_load_teams_referees')
      ->setDescription('Load Sinc Teams Referees');
  }
  protected function execute(InputInterface $input, OutputInterface $output)
  { 
    $dic     = $this->getApplication()->dic;
    $dataDir = $this->getApplication()->dataDir;
    
    echo sprintf("Load Sinc Teams Referees\n");
    
    $loader   = $dic['cerad_sinc_teams_referees_loader'];
    $teams = $loader->load($dataDir . '/SincTeamReferees.xlsx');
    
    file_put_contents($dataDir . '/SincTeamsReferees.yml', Yaml::dump($teams,10,2));
    
    echo sprintf("Load Sinc Teams Count %d\n",count($teams));
    
    return;
    
    $apiGames = $loader->load();
    
    file_put_contents($dataDir . '/ApiGames.yml', Yaml::dump($apiGames,10,2));
    
    $reporter = $dic['officials_reporter_excel'];
    $reporter->generate($apiGames);
    file_put_contents($dataDir . '/OfficialsReport.xlsx', $reporter->getContents());
    
    return;
  }
}
