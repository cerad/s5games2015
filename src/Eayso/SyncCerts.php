<?php
namespace Cerad\Component\Eayso;

use Cerad\Component\Excel\Loader;

class SyncCerts extends Loader
{
  protected $dbConn;
  protected $certTypeRepository;
  
  protected $record = [
    'aysoid'     => ['cols' => 'AYSOID'],
    'phone'      => ['cols' => 'HomePhone'],
    'email'      => ['cols' => 'Email'],
    'cert_desc'  => ['cols' => 'CertificationDesc'],
    'cert_date'  => ['cols' => 'CertDate'],
    'sar'        => ['cols' => 'SectionAreaRegion'],
    'name_first' => ['cols' => 'FirstName'],
    'name_last'  => ['cols' => 'LastName'],
    'mem_year'   => ['cols' => 'Membership Year'],
  ];
    
  public function __construct($dbConn,$certTypeRepository)
  {
    parent::__construct();
    
    $this->dbConn = $dbConn;
    $this->certTypeRepository = $certTypeRepository;
    
    $this->selectStmt = $dbConn->prepare('SELECT * FROM eayso_certs WHERE aysoid = ? AND role = ?');
  }
  protected function processCert($cert)
  {
    $this->selectStmt->execute([$cert['aysoid'],$cert['role']]);
    $certx = $this->selectStmt->fetch();
    if (!$certx) {
      $this->dbConn->insert('eayso_certs',$cert);
      $this->results['inserted']++;
      return;
    }
    $updates = [];
    foreach($cert as $key => $value) {
      if ($certx[$key] != $value) {
        switch($key) {
          case 'badge':
            $cmp = $this->certTypeRepository->compareBadges($cert['role'],$value,$certx['badge']);
            if ($cmp > 0) {
              $updates[$key] = $value;
              $updates['cert_date'] = $cert['cert_date'];
            }
          break;
          case 'mem_year':
            if ($value > $certx['mem_year']) $updates[$key] = $value;
            break;
          case 'cert_date':
            break;
          default:
            $updates[$key] = $value;
        }
      }
    }
    if(count($updates)) {
    //print_r($cert); print_r($certx); print_r($updates); die();
      $this->dbConn->update('eayso_certs',$updates, ['id' => $certx['id']]);
      $this->results['updated']++;
    }
    return;
  }
  protected function processItem($item)
  {
    $this->results['total']++;
    
    if (substr($item['mem_year'],0,2) !== 'MY') return;
    
    if ($item['mem_year'] < 'MY2012') return;
    
    $this->results['current']++;
    
    $certTypes = $this->certTypeRepository->findByCertDesc($item['cert_desc']);
    unset($item['cert_desc']);
    foreach($certTypes as $role => $badge)
    {
      $item['role']  = $role;
      $item['badge'] = $badge;
      $this->processCert($item);
    }
  }
  public function sync($inputFileName)
  {
    $this->results = [
      'file'     => $inputFileName,
      'total'    => 0,
      'current'  => 0,
      'inserted' => 0,
      'updated'  => 0,
    ];
    $fp = fopen($inputFileName,'rt');
    
    $headerRow = fgetcsv($fp);
    $this->processHeaderRow($headerRow);
    while($row = fgetcsv($fp)) {
      $item = $this->processDataRow($row);
      $this->processItem($item);
    }
    fclose($fp);
    return $this->results;
  }
}