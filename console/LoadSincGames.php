<?php
namespace Cerad\S5Games;

use Cerad\Component\Excel\Loader as BaseLoader;

class LoadSincGames extends BaseLoader
{
  protected $record = [
    'num'   => ['cols' => 'Number',  'req' => true],
    'date'  => ['cols' => 'Date',    'req' => true],
    'time'  => ['cols' => 'Time',    'req' => true],
    'div'   => ['cols' => 'Division','req' => true],
    'field' => ['cols' => 'Field',   'req' => true],
    'type'  => ['cols' => 'Type',    'req' => true],
    
    'homeTeamId'   => ['cols' => 'HomeID',  'req' => true],
    'homeTeamName' => ['cols' => 'Home',    'req' => true],
    'homeTeamClub' => ['cols' => 'HomeClub','req' => true],
    
    'awayTeamId'   => ['cols' => 'AwayID',  'req' => true],
    'awayTeamName' => ['cols' => 'Away',    'req' => true],
    'awayTeamClub' => ['cols' => 'AwayClub','req' => true],
    
    'project' => ['cols' => 'Event',  'req' => true],
    'updated' => ['cols' => 'Updated','req' => true],
  ];
  protected function processItem($item)
  {
    $item['date'] = $this->processDate($item['date']);
    $item['time'] = $this->processTime($item['time']);
    
    $item['updated'] = $this->excel->processDateTime($item['updated']);
    
    $item['teams'] = [
      'home' => ['id' => $item['homeTeamId'],'role' => 'home', 'name' => $item['homeTeamName'], 'club' => $item['homeTeamClub']],
      'away' => ['id' => $item['awayTeamId'],'role' => 'away', 'name' => $item['awayTeamName'], 'club' => $item['awayTeamClub']],
    ];
    unset($item['homeTeamId'  ]);
    unset($item['homeTeamName']);
    unset($item['homeTeamClub']);
    unset($item['awayTeamId'  ]);
    unset($item['awayTeamName']);
    unset($item['awayTeamClub']);
    
    $this->items[] = $item;
    return;
  //print_r($item); die();
  }
}
