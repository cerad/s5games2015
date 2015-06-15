<?php

namespace Cerad\Component\Eayso;

use Pimple\Container as Dic;

use Cerad\Component\Dbal\ConnectionFactory as DbConnFactory;

class Services
{
  public function __construct(Dic $dic)
  {
    $dic['cerad_eayso_db_conn'] = function(Dic $dic) {
      return DbConnFactory::create($dic['cerad_eayso_db_url']);
    };
    $dic['cerad_eayso_cert_type_repository'] = function() {
      return new CertTypeRepository();
    };
    $dic['cerad_eayso_cert_repository'] = function(Dic $dic) {
      return new CertRepository($dic['cerad_eayso_db_conn']);
    };
    $dic['cerad_eayso_sync_certs'] = function(Dic $dic) {
      return new SyncCerts(
        $dic['cerad_eayso_db_conn'],
        $dic['cerad_eayso_cert_type_repository']
      );
    };
    if (isset($dic['dic_commands'])) {
      
      $dicCommands = $dic['dic_commands'];
    
      $dic['cerad_eayso_sync_certs_command'] = function() {
        return new SyncCertsCommand();
      };
    }
  }
}