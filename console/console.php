#!/usr/bin/env php
<?php
namespace Cerad\S5Games;

require __DIR__ . '/../vendor/autoload.php';

use Pimple\Container;

use Symfony\Component\Console\Application;

$dic = new Container();

new Parameters($dic);
new Services  ($dic);

new \Cerad\Component\Eayso\Services($dic);

$app = new Application();

$app->add($dic['tran_sinc_spor_games_command']);
$app->add($dic['bounce_command']);
$app->add($dic['eayso_sync_certs_command']);

$app->dic = $dic;
$app->dataDir = __DIR__ . '/data';

$app->run();

