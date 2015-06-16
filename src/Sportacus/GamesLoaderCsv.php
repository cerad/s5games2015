<?php
namespace Cerad\S5Games;

use Cerad\Component\Excel\Loader as BaseLoader;

class LoadSporGames extends BaseLoader
{
  protected $record = [
    'id'           => ['cols' => 'id',    ],
    'num'          => ['cols' => 'IDstr', ],
    'status'       => ['cols' => 'Status',],
    'date'         => ['cols' => 'Date',  ],
    'time'         => ['cols' => 'Time',  ],
    'homeTeamName' => ['cols' => 'Home'   ],
    'awayTeamName' => ['cols' => 'Away'   ],
      
    'div'          => ['cols' => 'Division',    ],
    'lenGame'      => ['cols' => 'Length', ],
    'lenSlot'      => ['cols' => 'TSLength',],
    'fieldName'    => ['cols' => 'Location',  ],
    'regionName'   => ['cols' => 'Region',  ],
      
    'ref1Name'     => ['cols' => 'Ref1',  ],
    'ref2Name'     => ['cols' => 'Ref2',  ],
    'ref3Name'     => ['cols' => 'Ref3',  ],
    'ref4Name'     => ['cols' => 'Ref4',  ],
    'ref5Name'     => ['cols' => 'Ref5',  ],
    'ref1Email'    => ['cols' => 'email1',  ],
    'ref2Email'    => ['cols' => 'email2',  ],
    'ref3Email'    => ['cols' => 'email3',  ],
    'ref4Email'    => ['cols' => 'email4',  ],
    'ref5Email'    => ['cols' => 'email5',  ],
    
  ];
  protected function processItem($item)
  { 
    $item['date'] = $this->processDate($item['date']);
  //$item['dow' ] = $this->processDayOfWeek($item['date']);
    $item['time'] = $this->processTime($item['time']);
    
    $item['teams'] = [
      'home' => ['role' => 'home', 'name' => $item['homeTeamName']],
      'away' => ['role' => 'away', 'name' => $item['awayTeamName']],
    ];
    unset($item['homeTeamName']);
    unset($item['awayTeamName']);
    
    $item['officials'] = [
      1 => ['pos' => 1, 'name' => $item['ref1Name'], 'email' => $item['ref1Email']],
      2 => ['pos' => 2, 'name' => $item['ref2Name'], 'email' => $item['ref2Email']],
      3 => ['pos' => 3, 'name' => $item['ref3Name'], 'email' => $item['ref3Email']],
      4 => ['pos' => 4, 'name' => $item['ref4Name'], 'email' => $item['ref4Email']],
      5 => ['pos' => 5, 'name' => $item['ref5Name'], 'email' => $item['ref5Email']],
    ];
    unset($item['ref1Name']); unset($item['ref1Email']);
    unset($item['ref2Name']); unset($item['ref2Email']);
    unset($item['ref3Name']); unset($item['ref3Email']);
    unset($item['ref4Name']); unset($item['ref4Email']);
    unset($item['ref5Name']); unset($item['ref5Email']);
     
    //print_r($item); die();
    
    $this->items[] = $item;
  }
}
