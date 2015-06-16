<?php

namespace Cerad\App\S5Games;

use Pimple\Container as Dic;

//  Cerad\Component\Dbal\ConnectionFactory as DbConn;

class Services
{
  public function __construct(Dic $dic = null)
  {
    return $dic === null ? null : $this->register($dic);
  }
  public function register(Dic $dic)
  {
    $dic['dic_commands'] = function() {
      return new Dic();
    };
    $dicCommands = $dic['dic_commands'];
    
    $dicCommands['bounce_command'] = function() use ($dic) {
      return new BounceCommand($dic['app_data_dir'],$dic);
    };
  }
}