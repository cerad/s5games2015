<?php
namespace Cerad\App\S5Games;

use Cerad\Component\Dic\Dic as Dic;

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
    $dicCommands = $dic->get('dic_commands');
    
    $dicCommands['bounce_command'] = function() use ($dic) {
      return new BounceCommand($dic->get('app_data_dir'),$dic);
    };
  }
}