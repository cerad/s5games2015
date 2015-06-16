<?php
namespace Cerad\Component\Sportacus;

use Cerad\Component\Dic\Dic as Dic;

class Services
{
  public function __construct(Dic $dic = null)
  {
    return $dic === null ? null : $this->register($dic);
  }
  public function register(Dic $dic)
  {
    $dic['cerad_sportacus_games_loader_api'] = function(Dic $dic) {
      return new GamesLoaderApi(
        $dic->get('cerad_sportacus_api_base_uri'),
        $dic->get('cerad_eayso_cert_repository' )
      );
    };
    $dic['cerad_sportacus_officials_reporter_excel'] = function() {
      return new OfficialsReporterExcel();
    };
    $dic['cerad_sportacus_games_comparer_sinc'] = function() {
      return new GamesComparerSinc();
    };
    if ($dic->has('dic_commands')) {
      
      $dicCommands = $dic->get('dic_commands');
    
      $dicCommands['cerad_sportacus_officials_report_command'] = function() use ($dic) {
        return new OfficialsReportCommand($dic->get('app_data_dir'),$dic);
      };
      $dicCommands['cerad_sportacus_games_compare_sinc_command'] = function() use ($dic) {
        return new GamesCompareSincCommand($dic->get('app_data_dir'),$dic);
      };
    }
  }
}