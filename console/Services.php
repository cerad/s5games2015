<?php

namespace Cerad\S5Games;

use Pimple\Container;

use Cerad\Component\Dbal\ConnectionFactory;

class Services
{
  public function __construct(Container $dic)
  {
    $dic['tran_sinc_spor_games_command'] = function(Container $dic) {
      return new TranSincSporGamesCommand();
    };
    $dic['referee_report_command'] = function(Container $dic) {
      return new RefereeReportCommand();
    };
    return;
    /* ======================================
     * Connections
     */
    $container->set('db_conn_ng2012',function(Container $container)
    {
      return ConnectionFactory::create($container->get('db_url_ng2012'));
    });
    $container->set('db_conn_ng2014',function(Container $container)
    {
      return ConnectionFactory::create($container->get('db_url_ng2014'));
    });
    $container->set('db_conn_tourns',function(Container $container)
    {
      return ConnectionFactory::create($container->get('db_url_tourns'));
    });
    $container->set('db_conn_users',function(Container $container)
    {
      return ConnectionFactory::create($container->get('db_url_users'));
    });
    /* ===========================================
     * Commands
     */
    $container->set('unload_ng2012_command',function(Container $container)
    {
      return new UnloadNG2012Command($container->get('db_conn_ng2012'));
    },'command');
    $container->set('unload_ng2014_command',function(Container $container)
    {
      return new UnloadNG2014Command($container->get('db_conn_ng2014'));
    },'command');
    $container->set('unload_tourns_command',function(Container $container)
    {
      return new UnloadTournsCommand($container->get('db_conn_tourns'));
    },'command');
    $container->set('load_users_command',function(Container $container)
    {
      return new LoadUsersCommand($container->get('db_conn_users'));
    },'command');
  }
}