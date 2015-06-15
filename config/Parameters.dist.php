<?php
namespace Cerad\App\S5Games;

use Pimple\Container;

class Parameters
{
  public function __construct(Container $dic)
  {
    $dic['cerad_eayso_db_url'] = 'mysql://USER:PASS@HOST/eayso';
    
    $dic['cerad_sportacus_api_base_uri'] = 'http://local.sportacus.zayso.org';
  //$dic['cerad_sportacus_api_base_uri'] = 'http://sportac.us';

  }
}