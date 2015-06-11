<?php
namespace Cerad\S5Games;

use Pimple\Container;

class Parameters
{
  public function __construct(Container $dic)
  {
    $dic['db_url_games'] = 'mysql://USER:PASS@localhost/sos';
  }
}