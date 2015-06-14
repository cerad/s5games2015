<?php
namespace Cerad\Component\Eayso;

use Doctrine\DBAL\Connection as DbConn;

class CertRepository
{
  protected $dbConn;
    
  public function __construct(DbConn $dbConn)
  {
    $this->dbConn = $dbConn;
  }
  public function findCertsByAysoid($aysoids,$roles=[])
  {
    $aysoids = is_array($aysoids) ? $aysoids : [$aysoids];
    $roles   = is_array($roles)   ? $roles   : [$roles];
    
    if (count($aysoids) < 1) return [];
    
    if (count($roles) < 1) {
      $sql = 'SELECT * FROM eayso_certs WHERE aysoid IN (?);';
      return $this->dbConn->executeQuery($sql,[$aysoids],[DbConn::PARAM_STR_ARRAY])->fetchAll();
    }
    $sql = 'SELECT * FROM eayso_certs WHERE aysoid IN (?) AND role IN (?);';
    return $this->dbConn->executeQuery(
      $sql,
      [$aysoids,$roles],
      [DbConn::PARAM_STR_ARRAY,DbConn::PARAM_STR_ARRAY]
    )->fetchAll();
  }
}