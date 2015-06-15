#!/usr/bin/env php
<?php
namespace Cerad\S5Games;

require __DIR__ . '/../vendor/autoload.php';

use Pimple\Container;

use Symfony\Component\Console\Application;

$dic = new Container();

new Parameters($dic);
new Services  ($dic);

new \Cerad\Component\Sinc \Services($dic);
new \Cerad\Component\Eayso\Services($dic);

$app = new Application();

$dicCommands = $dic['dic_commands'];

foreach($dicCommands->keys() as $serviceId) {
  $app->add($dicCommands[$serviceId]);
}
$app->dic = $dic;
$app->dataDir = __DIR__ . '/data';

$app->run();

