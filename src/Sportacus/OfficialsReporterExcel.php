<?php
namespace Cerad\Component\Sportacus;

use Cerad\Component\Excel\Reporter;

class OfficialsReporterExcel extends Reporter
{
  /* ===================================================================
   * Deal with widths and such
   */
  protected $colWidths = [
    'Num'   =>  6,
    'Date'  => 10,
    'DOW'   =>  6,
    'Time'  =>  8,
    'Field' => 16,
    'Div'   =>  6,
    'Role'  => 10,
    'Name'  => 30,
    'Home'  => 30, 'Away'  => 30,
    
    'Referee' => 30,'AR1' => 30,'AR2' => 30,'4th' => 10,'Other' => 10,
    'Email' => 30, 'Phone' => 12,
    'RR' => 6, 'Badge' => 12,
  ];
  protected function getOfficialName($official,$extended = false)
  {
    if (!$official['id']) return null;
    
    $name = sprintf('%s, %s',$official['nameLast'],$official['nameFirst']);
    
    if (!$extended) return $name;
    
    $badge = $official['aysoRefereeBadge'] ? substr($official['aysoRefereeBadge'],0,3) : '???';
   
    return sprintf('%s %s %s',$official['regionName'],$badge,$name);
  }
  protected function getDivisionName($game)
  {
    $div  = $game['divName'];
    $name = $game['teams']['home']['name'];
    $pos  = strpos($name,$div);
    
    return $pos === false ? $div : substr($name,$pos,4);
  }
  protected function transformPhoneNumber($phone)
  {
    if (!$phone) return null;
    return sprintf('%s.%s.%s',
      substr($phone,0,3),
      substr($phone,3,3),
      substr($phone,6)
    );
  }
  /* =====================================================
   * Game schedule with officials sheet
   */
  protected function generateGamesSheet($ws,$games)
  {
    $ws->setTitle('Games');

    $headers = array(
      'Num','Date','Time','Field','Div','Home','Away','Referee','AR1','AR2','4th','Other'
    );
    $this->writeHeaders($ws,1,$headers,$this->colWidths);
    $row = 2;
        
    usort($games,function($game1,$game2) {
      $cmp = strcmp($game1['date'],$game2['date']);
      if ($cmp) return $cmp;
      
      $div1 = $this->getDivisionName($game1);
      $div2 = $this->getDivisionName($game2);
      $cmp = strcmp($div1,$div2);
      if ($cmp) return $cmp;
      
      $cmp = strcmp($game1['fieldName'],$game2['fieldName']);
      if ($cmp) return $cmp;
      
      $cmp = strcmp($game1['time'],$game2['time']);
      if ($cmp) return $cmp;
      
      return 0;
    });
    foreach($games as $game)
    {
      $values = array();
      $values[] = $game['num'];
      $values[] = $game['date'];
      $values[] = $game['time'];
      $values[] = $game['fieldName'];
      $values[] = $this->getDivisionName($game);
      $values[] = $game['teams']['home']['name'];
      $values[] = $game['teams']['away']['name'];
    
      // TODO: sort officials by role?
      foreach($game['officials'] as $official)
      {
        $values[] = $this->getOfficialName($official,true);
      }
      $this->setRowValues($ws,$row++,$values);
    }
  }
  /* ==========================================================
   * Assignments
   */
  protected function generateAssignmentsSheet($ws,$games)
  {
    $ws->setTitle('Assignments');

    $headers = array(
      'Name','Role',
      'Num','Date','Time','Field','Div','Home','Away',
      'RR','Badge',
    );
    $this->writeHeaders($ws,1,$headers,$this->colWidths);
    $row = 2;
        
    $officials = array();
    foreach($games as $game)
    {
      for($pos = 1; $pos < 6; $pos++)
      {
        if (isset($game['officials'][$pos]))
        {
          $official = $game['officials'][$pos];
          $name = $this->getOfficialName($official);
          if ($name)
          {
            switch($pos)
            {
              case 1: $role = 'Referee'; break;
              case 2: $role = 'AR1';     break;
              case 3: $role = 'AR2';     break;
              case 4: $role = '4th';     break;
              case 5: $role = 'Other';   break;
            }
            $officials[$name][] = [
              'name' => $name, 
              'role' => $role, 
              'game' => $game,
              'official' => $official,
            ];
          }
        }
      }
    }
    usort($officials,function($official1,$official2) {
      return strcmp($official1[0]['name'],$official2[0]['name']);
    });
    foreach($officials as $officialGames)
    {
      usort($officialGames,function($item1,$item2) {
        
        $cmp = strcmp($item1['game']['date'], $item2['game']['date']);
        if ($cmp) return $cmp;
        
        $cmp = strcmp($item1['game']['time'], $item2['game']['time']);
        if ($cmp) return $cmp;
        
        // Conflicting time slots, hmmm
        return 0;
      });
      foreach($officialGames as $officialGame)
      {
        $game = $officialGame['game'];

        $values = array();
        $values[] = $officialGame['name'];
        $values[] = $officialGame['role'];
      
        $values[] = $game['num'];
        $values[] = $game['date'];
        $values[] = $game['time'];
        $values[] = $game['fieldName'];
        $values[] = $this->getDivisionName($game);
        $values[] = $game['teams']['home']['name'];
        $values[] = $game['teams']['away']['name'];

        $values[] = $officialGame['official']['regionName'];
        $values[] = $officialGame['official']['aysoRefereeBadge'];
        
        $this->setRowValues($ws,$row++,$values);
      }
      $row++;
    }
  }
  /* ======================================================
   * Officials
   */
  protected function generateOfficialsSheet($ws,$games)
  {        
    $officials = array();
    foreach($games as $game)
    {
      for($pos = 1; $pos < 6; $pos++)
      {
        if (isset($game['officials'][$pos]))
        {
          $official = $game['officials'][$pos];
          $name = $this->getOfficialName($official);
          if ($name)
          {
            if (!isset($officials[$name])) {
              $officialData = [
                'official' => $official,
                'name'     => $name,
                'total'    => 0,
                'referee'  => 0,
                'ar'       => 0,
                '4th'      => 0,
                'other'    => 0,
              ];
            }
            else $officialData = $officials[$name];
            
            $officialData['total']++;
            
            switch($pos)
            {
              case 1: $officialData['referee']++; break;
              case 2: $officialData['ar'     ]++; break;
              case 3: $officialData['ar'     ]++; break;
              case 4: $officialData['4th'    ]++; break;
              case 5: $officialData['other'  ]++; break;
            }
            $officials[$name] = $officialData;
          }
        }
      }
    }
    usort($officials,function($official1,$official2) {
      return strcmp($official1['name'],$official2['name']);
    });
    
    /* ==========================================
     * Generate sheet
     */
    $ws->setTitle('Officials');

    $headers = [
      'Name','Badge','Tot','Ref','AR','4th','Oth','Email','Phone',
      'Region','AYSOID','Mem Year','Youth',
    ];
    $cw = 4;
    $widths = array_replace($this->colWidths,[
      'Tot' => $cw, 'Ref' => $cw, 'AR' => $cw, '4th' => $cw, 'Oth' => $cw,
      'Region' => 8, 'AYSOID' => 10, 'Mem Year' => 10, 'Youth' => 6,
    ]);
    $this->writeHeaders($ws,1,$headers,$widths);
    $row = 2;
    
    foreach($officials as $officialInfo)
    {
      $official = $officialInfo['official'];
      
      $values = array();
      $values[] = $officialInfo['name'];
      $values[] = $official    ['aysoRefereeBadge'];
      $values[] = $officialInfo['total'];
      $values[] = $officialInfo['referee'];
      $values[] = $officialInfo['ar'];
      $values[] = $officialInfo['4th'];
      $values[] = $officialInfo['other'];
      
      $values[] = $official['email'];
      $values[] = $this->transformPhoneNumber($official['phone']);
      $values[] = $official['regionName'];
      $values[] = $official['aysoId'];
      $values[] = $official['aysoMemYear'];
      $values[] = $official['isYouth'] ? 'Youth' : null;
      
      $this->setRowValues($ws,$row++,$values);
    }
  }
  /* ==========================================================
   * Main entry point
   */
  public function generate($games)
  {
    $this->ss = $ss = $this->createSpreadSheet();
        
    $si = 0;
        
    $this->generateAssignmentsSheet($ss->createSheet($si++),$games);
    $this->generateGamesSheet      ($ss->createSheet($si++),$games);
    $this->generateOfficialsSheet  ($ss->createSheet($si++),$games);
        
    // Finish up
    $ss->setActiveSheetIndex(0);
        
    return $ss;
  }
}
