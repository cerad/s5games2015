<?php
namespace Cerad\Component\Sinc;

use Cerad\Component\Excel\Loader as BaseLoader;

class TeamsRefereesLoader extends BaseLoader
{
  /*
   * Division	Division_Name	Team	Club	Region	Name	AYSO ID	Youth Ref	Email	Phone	Level of Certification	Certification Confirmed	Comfort Level	Comfort Level	Player on Team	Name	AYSO ID	Youth Ref	\Email	Phone	Level of Certification	Certification Confirmed	Comfort Level	Comfort Level	Player on Team
   */
  protected $record = [
    'div'    => ['cols' => 'Division'],
    'name'   => ['cols' => 'Team'    ],
    'club'   => ['cols' => 'Club'    ],
    'region' => ['cols' => 'Region'  ],
    
    'refName1'   => ['cols' => 'Name_1'  ],
    'refAysoId1' => ['cols' => 'Name_1', 'plus' => 1 ],
    'refYouth1'  => ['cols' => 'Name_1', 'plus' => 2 ],
    'refEmail1'  => ['cols' => 'Name_1', 'plus' => 3 ],
    'refPhone1'  => ['cols' => 'Name_1', 'plus' => 4 ],
    'refBadge1'  => ['cols' => 'Name_1', 'plus' => 5 ],
    'refCR1'     => ['cols' => 'Name_1', 'plus' => 7 ],
    'refAR1'     => ['cols' => 'Name_1', 'plus' => 8 ],
    'refPlayer1' => ['cols' => 'Name_1', 'plus' => 9 ],
    
    'refName2'   => ['cols' => 'Name_2'  ],
    'refAysoId2' => ['cols' => 'Name_2', 'plus' => 1 ],
    'refYouth2'  => ['cols' => 'Name_2', 'plus' => 2 ],
    'refEmail2'  => ['cols' => 'Name_2', 'plus' => 3 ],
    'refPhone2'  => ['cols' => 'Name_2', 'plus' => 4 ],
    'refBadge2'  => ['cols' => 'Name_2', 'plus' => 5 ],
    'refCR2'     => ['cols' => 'Name_2', 'plus' => 7 ],
    'refAR2'     => ['cols' => 'Name_2', 'plus' => 8 ],
    'refPlayer2' => ['cols' => 'Name_2', 'plus' => 9 ],
  ];
  protected function processItem($item)
  {
    $team = [];
    foreach(['div','name','club','region'] as $key) {
      $team[$key] = $item[$key];
    }
    $map = [
      'name'    => 'refName',
      'aysoId'  => 'refAysoId',
      'youth'   => 'refYouth',
      'email'   => 'refEmail',
      'phone'   => 'refPhone',
      'badge'   => 'refBadge',
      'crLevel' => 'refCR',
      'arLevel' => 'refAR',
      'player'  => 'refPlayer',
    ];
    $team['referees'] = [];
    for($i = 1; $i < 3; $i++) {
      if (strlen(trim($item['refName' . $i]))) {
        $referee = [];
        foreach($map as $des => $src)
        {
          $referee[$des] = $item[$src . $i];
        }
        $team['referees'][] = $referee;
      }
    }
    $this->items[] = $team;
  }
}