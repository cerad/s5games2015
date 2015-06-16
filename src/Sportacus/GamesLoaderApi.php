<?php
namespace Cerad\Component\Sportacus;

use GuzzleHttp\Client as GuzzleClient;

class GamesLoaderApi
{
  protected $apiBaseUri; // 'http://local.sportacus.zayso.org'
  protected $eaysoCertRepository;
  
  public function __construct($apiBaseUri, $eaysoCertRepository = null)
  {
    $this->apiBaseUri = $apiBaseUri;
    $this->eaysoCertRepository = $eaysoCertRepository;
  }
  public function load($pathUri = null)
  {
    $pathUri = $pathUri !== null ? $pathUri : '/api/projects/19/games?pin=9345&dates=20150619-20150620';
    
    $guzzleClient = new GuzzleClient();
    $guzzleResponse = $guzzleClient->get($this->apiBaseUri . $pathUri);
    
    $data = json_decode($guzzleResponse->getBody()->getContents(),true);
      
    $divs     = $data['divs']; // TODO: Process from team name?
    $games    = $data['games'];
    $teams    = $data['teams'];
    $fields   = $data['fields'];
    $regions  = $data['regions'];
    $persons  = $this->processEaysoInfo($data['persons']);
    $projects = $data['projects'];
    
    $gamesx = [];
    foreach($games as $game) {
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
        else {
          $teamx['divName'] = $gamex['divName'];
          $teamx['divName'] = 'XXX';
        }
        $teamx['regionName'] = $regions[$team['regionId']]['name'];
        
        $teamx['score'] = $game[$role . 'TeamScore'];
        
        $gamex['teams'][$role] = $teamx;
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
      $gamesx[(integer)$gamex['num']] = $gamex;
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
