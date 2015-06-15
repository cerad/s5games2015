<?php
namespace Cerad\Component\Sportacus;

use GuzzleHttp\Client as GuzzleClient;

class GamesLoaderApi
{
  protected $eaysoCertRepository;
  
  public function __construct($eaysoCertRepository = null)
  {
    $this->eaysoCertRepository = $eaysoCertRepository;
  }
  public function load($pathUri = null, $baseUri = null)
  {
    $pathUri = $pathUri !== null ? $pathUri : '/api/projects/19/games?pin=9345&dates=20150619-20150620';
    $baseUri = $baseUri !== null ? $baseUri : 'http://local.sportacus.zayso.org';
    
    $guzzleClient = new GuzzleClient();
    $baseUri = 'http://local.sportacus.zayso.org';
    $guzzleResponse = $guzzleClient->get($baseUri . $pathUri);
    
    $data = json_decode($guzzleResponse->getBody()->getContents(),true);
    echo sprintf("Games %s\n",count($data['games']));
      
    $divs     = $data['divs']; // TODO: Process from team name?
    $games    = $data['games'];
    $teams    = $data['teams'];
    $fields   = $data['fields'];
    $regions  = $data['regions'];
    $persons  = $this->processEaysoInfo($data['persons']);
    $projects = $data['projects'];
    
    $gamesx = [];
    foreach($games as $game) {//print_r($game); die();
      $gamex = [];
      foreach(['id','num','date','time','length','lengthSlot','status'] as $key) {
        $gamex[$key] = $game[$key];
      }
      $gamex['divName']     = $divs    [$game['divId']]    ['name'];
      $gamex['fieldName']   = $fields  [$game['fieldId']]  ['name'];
      $gamex['projectName'] = $projects[$game['projectId']]['name'];
     
      $gamex['teams'] = [];
      foreach(['home','away'] as $role) {
        $team = $teams[$game[$role . 'TeamId']];
        $teamx = [];
        foreach(['id','name','colors','coachName','coachEmail'] as $keyx) {
          $teamx[$keyx] = $team[$keyx];
        }
        if (isset($team['divId'])) {
          $teamx['divName'] = $divs[$team['divId'   ]]['name'];
        }
        else { // print_r($team); die();
          $teamx['divName'] = $gamex['divName'];
          $teamx['divName'] = 'XXX';
          // print_r($game); die(); 
        }
        $teamx['regionName'] = $regions[$team['regionId']]['name'];
        
        $teamx['score'] = $game[$role . 'TeamScore'];
        
        $gamex['teams'][$role] = $teamx;
        
        //print_r($teamx); die();
      }
      $gamex['officials'] = [];
      for($slot = 1; $slot < 6; $slot++)
      {
        $officialx = [
          'pos'  => $slot,
          'slot' => $slot,
          'id'   => null,
        ];
        $personId = $game['ref' . $slot . 'Id'];
        if ($personId)
        {
          $person = $persons[$personId]; // print_r($person); die();
          $officialx = array_replace($officialx,$person);
          
          $officialx['regionName'] = $regions[$person['regionId']]['name'];
          unset($officialx['regionId']);
          
          //print_r($officialx); die();
        }
        $gamex['officials'][$slot] = $officialx;
      }
      $gamesx[$gamex['num']] = $gamex;
    //print_r($gamex); die();
    }
    return $gamesx;
  }
  protected function processEaysoInfo($persons)
  {
    if (!$this->eaysoCertRepository) return $persons;
    
    $aysoids = [];
    foreach($persons as $person) {
      if ($person['aysoId']) $aysoids[] = $person['aysoId'];
    }
    $certs = $this->eaysoCertRepository->findCertsByAysoid($aysoids,'Referee');
    $badges = [];
    foreach($certs as $cert) { 
      $badges[$cert['aysoid']] = $cert['badge'];
    }
    foreach($persons as $id => $person) {
      $person['aysoRefereeBadge'] = isset($badges[$person['aysoId']]) ? $badges[$person['aysoId']] : null;
      $persons[$id] = $person;
    }
    return $persons;
  }
}
