<?php
namespace Cerad\S5Games;

use Pimple\Container;

class Parameters
{
  public function __construct(Container $dic)
  {
    $dic['games_db_url'] = 'mysql://USER:PASS@localhost/sportacus';
    $dic['eayso_db_url'] = 'mysql://USER:PASS@localhost/eayso';
  }
}