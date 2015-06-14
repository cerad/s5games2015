<?php
namespace Cerad\Component\Eayso;

use Symfony\Component\Yaml\Yaml;

class CertTypeRepository
{
  protected $certTypes;
  protected $certDescs;
    
  public function __construct()
  {
    $file = __DIR__ . '/cert_types.yml';
    $yaml = Yaml::parse(file_get_contents($file));
    $this->certTypes = $yaml['certTypes'];
    
    $certDescs = [];
    foreach($this->certTypes as $role => $badges) {
      foreach($badges as $badge => $descs) {
        foreach($descs as $desc) {
          $certDescs[$desc][$role] = $badge;
        }
      }
    }
    $this->certDescs = $certDescs;
  }
  public function findByCertDesc($certDesc)
  {
    // Need to handle comma delimited nonsense
    $parts = explode(',',$certDesc);
    $certs = [];
    foreach($parts as $part) {
      $part = trim($part);
      if (isset($this->certDescs[$part])) {
        $certs = array_merge($certs,$this->certDescs[$part]);
      }
      else {
        echo sprintf("*** No cert record for '%s'\n",$certDesc);die();
      }
    }
    return $certs;
  }
  // Referee Intermediate National
  public function compareBadges($role,$badge1,$badge2)
  {
    if ($badge1 == $badge2) return 0;
        
    $badges = $this->certTypes[$role];
    foreach($badges as $badge => $descs) {
      if ($badge == $badge1) return -1;
      if ($badge == $badge2) return  1;
    }
    // Invalid arg exception?
    return 0;
  }
}

?>
