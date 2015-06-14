<?php
namespace Cerad\Component\Eayso;

use Symfony\Component\Console\Command\Command;
//  Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//  Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Yaml\Yaml;
//  Doctrine\DBAL\Connection;

class SyncCertsCommand extends Command
{
  protected function configure()
  {
    $this
      ->setName('eayso_sync_certs')
      ->setDescription('Eayso Sync Certs');
  }
  protected function execute(InputInterface $input, OutputInterface $output)
  { 
    echo sprintf("Eayso Sync Certs\n");
    
    $dic     = $this->getApplication()->dic;
    $dataDir = $this->getApplication()->dataDir . '/eayso';
    
    $syncCerts = $dic['eayso_sync_certs'];
    $files = [
      '/CertRefereeAssistant.csv',
      '/CertRefereeRegional.csv',
      '/CertRefereeIntermediate.csv',
      '/CertRefereeAdvance.csv',
      '/CertRefereeNational.csv',
    ];
    foreach($files as $file)
    {
      $results = $syncCerts->sync($dataDir . $file);
      print_r($results);
    }
  }
}
