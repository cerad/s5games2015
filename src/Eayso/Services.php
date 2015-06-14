<?php

namespace Cerad\Component\Eayso;

use Pimple\Container as Dic;

use Cerad\Component\Dbal\ConnectionFactory as DbConnFactory;

class Services
{
  public function __construct(Dic $dic)
  {
    $dic['eayso_db_conn'] = function(Dic $dic) {
      return DbConnFactory::create($dic['eayso_db_url']);
    };
    $dic['eayso_cert_type_repository'] = function() {
      return new CertTypeRepository();
    };
    $dic['eayso_sync_certs'] = function(Dic $dic) {
      return new SyncCerts(
        $dic['eayso_db_conn'],
        $dic['eayso_cert_type_repository']
      );
    };
    $dic['eayso_sync_certs_command'] = function() {
      return new SyncCertsCommand();
    };

  }
}