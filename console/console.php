#!/usr/bin/env php
<?php
namespace Cerad\S5Games;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/Parameters.php';

use Pimple\Container;

use Symfony\Component\Console\Application;

$dic = new Container();

$dic['app_data_dir'] = __DIR__ . '/data';

new \Parameters($dic);
new  Services  ($dic);

new \Cerad\Component\Sinc     \Services($dic);
new \Cerad\Component\Eayso    \Services($dic);
new \Cerad\Component\Sportacus\Services($dic);

$app = new Application();

$dicCommands = $dic['dic_commands'];

foreach($dicCommands->keys() as $serviceId) {
  $app->add($dicCommands[$serviceId]);
}

$app->run();

