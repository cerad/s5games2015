<?php

namespace Cerad\Component\Sinc;

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
    $dic['cerad_sinc_teams_referees_loader'] = function() {
      return new TeamsRefereesLoader();
    };
    if (isset($dic['dic_commands'])) {
      $dicCommands = $dic['dic_commands'];
    
      $dicCommands['cerad_sinc_teams_referees_command'] = function() {
        return new TeamsRefereesCommand();
      };
    }
  }
}