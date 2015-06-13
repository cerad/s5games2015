<?php
namespace Cerad\S5Games;

use Pimple\Container;

class Parameters
{
  public function __construct(Container $dic)
  {
    $dic['db_url_games'] = 'mysql://impd:impd894@localhost/sportacus';
  }
}