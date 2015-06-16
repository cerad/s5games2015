<?php
namespace Cerad\App\S5Games;

error_reporting(E_ALL);
date_default_timezone_set('America/Chicago');

require '../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;

use Zend\Diactoros\Response      as Response;
use Zend\Diactoros\ServerRequest as Request;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter as ResponseEmitter;

use Cerad\Component\Dic\Dic as Dic;
use Interop\Container\ContainerInterface as DicInterface;

use Cerad\Component\Router\Router;


$dic = new Dic();
new  Parameters($dic);
new \Cerad\Component\Eayso    \Services($dic);
new \Cerad\Component\Sportacus\Services($dic);

/* Request handlers */
$dic['index_route'] = $dic->protect(function(RequestInterface $request, ResponseInterface $response) {
  ob_start();
  require 'views/index.html.php';
  $response->getBody()->write(ob_get_clean());
  return [$request,$response];
});
$dic['officials_report_route'] = 
  $dic->protect(function(RequestInterface $request, ResponseInterface $response) use($dic) {
  
  $loader   = $dic->get('cerad_sportacus_games_loader_api');
  $apiGames = $loader->load();
  
  $reporter = $dic->get('cerad_sportacus_officials_reporter_excel');
  $reporter->generate($apiGames);

  $response->getBody()->write($reporter->getContents());
  
  $outFileName = 'OfficialsReport' . date('Ymd-Hi') . '.' . $reporter->getFileExtension();

  $response = $response->withHeader('Content-Type', $reporter->getContentType());
  $response = $response->withHeader('Content-Disposition', sprintf('attachment; filename="%s"',$outFileName));

  return [$request,$response];
});

$router = new Router();
$router->addRoute('index_route',           'GET','/');
$router->addRoute('officials_report_route','GET','/officials/report');

$request = ServerRequestFactory::fromGlobals();
$response = new Response();

$route    = $router->dispatch($request->getMethod(),$request->getUri()->getPath());
$action   = $dic->get($route['name']);
$rr       = $action($request,$response);
$response = $rr[1];

// Emit it
$emitter = new ResponseEmitter();
$emitter->emit($response);
