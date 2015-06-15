<?php

namespace Cerad\S5Games;

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
    $dic['api_games_loader'] = function(Dic $dic) {
      return new ApiGamesLoader($dic['cerad_eayso_cert_repository']);
    };
    
    $dic['tran_sinc_spor_games_command'] = function() {
      return new TranSincSporGamesCommand();
    };
    $dic['officials_reporter_excel'] = function(Dic $dic) {
      return new OfficialsReporterExcel();
    };
    //$dic['officials_report_command'] = function(Dic $dic) {
    //  return new OfficialsReportCommand($dic['officials_reporter_excel']);
    //};
    $dicCommands = $dic['dic_commands'];
    
    $dicCommands['bounce_command'] = function() {
      return new BounceCommand();
    };
  }
}