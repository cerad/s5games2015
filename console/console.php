#!/usr/bin/env php
<?php
namespace Cerad\App\S5Games;

error_reporting(E_ALL);
date_default_timezone_set('America/Chicago');

require __DIR__ . '/../vendor/autoload.php';

use Cerad\Component\Dic\Dic as Dic;

use Symfony\Component\Console\Application;

$dic = new Dic();

$dic['app_data_dir'] = __DIR__ . '/data';

new Parameters($dic);
new Services  ($dic);

new \Cerad\Component\Sinc     \Services($dic);
new \Cerad\Component\Eayso    \Services($dic);
new \Cerad\Component\Sportacus\Services($dic);

$app = new Application();

$dicCommands = $dic['dic_commands'];

foreach($dicCommands->keys() as $serviceId) {
  $app->add($dicCommands->get($serviceId));
}

$app->run();

