<?php
namespace Cerad\Component\Sinc;

use Pimple\Container as Dic;

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
    $dic['cerad_sinc_games_loader_excel'] = function() {
      return new GamesLoaderExcel();
    };
    if (isset($dic['dic_commands'])) {
      
      $dicCommands = $dic['dic_commands'];
    
      $dicCommands['cerad_sinc_teams_referees_command'] = function() {
        return new TeamsRefereesCommand();
      };
    }
  }
}