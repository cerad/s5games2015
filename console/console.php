#!/usr/bin/env php
<?php
namespace Cerad\S5Games;

require __DIR__ . '/../vendor/autoload.php';

use Pimple\Container;

use Symfony\Component\Console\Application;

$dic = new Container();

new Parameters($dic);
new Services  ($dic);

$app = new Application();

$app->add($dic['tran_sinc_spor_games_command']);
$app->add($dic['referee_report_command']);
$app->add($dic['bounce_command']);

$app->run();

