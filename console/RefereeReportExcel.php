<?php
namespace Cerad\S5Games;

use Cerad\Component\Excel\Generator as Reporter;
/// Cerad\Bundle\PersonBundle\DataTransformer\PhoneTransformer;

class RefereeReportExcel extends Reporter
{
  protected $phoneTransformer;
    
  protected $lodgingNightsHeaders;
  protected $availabilityDaysHeaders;
    
  public function __construct()
  {
    //$this->phoneTransformer = new PhoneTransformer();
  }
    protected function setColumnWidths($ws,$widths)
    {
        $col = 0;
        foreach($widths as $width)
        {
            $ws->getColumnDimensionByColumn($col++)->setWidth($width);
        }
    }
    protected function setRowValues($ws,$row,$values)
    {
        $col = 0;
        foreach($values as $value)
        {
            $ws->setCellValueByColumnAndRow($col++,$row,$value);
        }
    }
  protected function generateGamesSheet($ws,$games)
  {
    $ws->setTitle('Games');

    $headers = array(
      'Num','Date','Time','Field','Div','Home','Away','Referee','AR1','AR2','4th','Other'
    );
        
    $this->writeHeaders($ws,1,$headers);
    $row = 2;
        
    foreach($games as $game)
    {
      $divsx = explode(' ',$game['div']);
      $div = $divsx[count($divsx) - 1];
      
      $values = array();
      $values[] = $game['num'];
      $values[] = $game['date'];
      $values[] = $game['time'];
      $values[] = $game['fieldName'];
      $values[] = $div;
      $values[] = $game['teams']['home']['name'];
      $values[] = $game['teams']['away']['name'];
    
      foreach($game['officials'] as $official)
      {
        $values[] = $official['name'];
      }
      $this->setRowValues($ws,$row++,$values);
    }
  }
  protected function generateAssignmentsSheet($ws,$games)
  {
    $ws->setTitle('Assignments');

    $headers = array(
      'Name','Role',
      'Num','Date','Time','Field','Div','Home','Away',
    );
        
    $this->writeHeaders($ws,1,$headers);
    $row = 2;
        
    $officials = array();
    foreach($games as $game)
    {
      for($pos = 1; $pos < 6; $pos++)
      {
        if (isset($game['officials'][$pos]))
        {
          $official = $game['officials'][$pos];
          if ($official['name'])
          {
            $name = $official['name'];
            switch($pos)
            {
              case 1: $role = 'Referee'; break;
              case 2: $role = 'AR1';     break;
              case 3: $role = 'AR2';     break;
              case 4: $role = '4th';     break;
              case 5: $role = 'Other';   break;
            }
            $officials[$name][] = ['name' => $name, 'role' => $role, 'game' => $game];
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
        
        // Conflicting slots, hmmm
        return 0;
      });
      foreach($officialGames as $officialGame)
      {
        $game = $officialGame['game'];
      
        $divsx = explode(' ',$game['div']);
        $div = $divsx[count($divsx) - 1];
      
        $values = array();
        $values[] = $officialGame['name'];
        $values[] = $officialGame['role'];
      
        $values[] = $game['num'];
        $values[] = $game['date'];
        $values[] = $game['time'];
        $values[] = $game['fieldName'];
        $values[] = $div;
        $values[] = $game['teams']['home']['name'];
        $values[] = $game['teams']['away']['name'];

        $this->setRowValues($ws,$row++,$values);
      }
      $row++;
    }
  }
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
          if ($official['name'])
          {
            $name = $official['name'];
            if (!isset($officials[$name])) {
              $officialData = [
                'official' => $official,
                'total' => 0,
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
      return strcmp($official1['official']['name'],$official2['official']['name']);
    });
    
    /* ==========================================
     * Generate sheet
     */
    $ws->setTitle('Officials');

    $headers = [
      'Name','Total','Referee','AR','4th','Other','Email','Phone'
    ];    
    $this->writeHeaders($ws,1,$headers);
    $row = 2;
    
    foreach($officials as $officialInfo)
    {
      $official = $officialInfo['official'];
      
      $values = array();
      $values[] = $official['name'];
      $values[] = $officialInfo['total'];
      $values[] = $officialInfo['referee'];
      $values[] = $officialInfo['ar'];
      $values[] = $officialInfo['4th'];
      $values[] = $officialInfo['other'];
      $values[] = $official['email'];
      
      $this->setRowValues($ws,$row++,$values);
    }
  }
    /* ===================================================================
     * Deal with widths and such
     */
    protected $widths = array
    (
        'Status'       => 8,
        'Applied Date' => 16,
        'USSF ID'    => 18,
        'Official'   => 24,
        'Email'      => 24,
        'Cell Phone' => 14,
        'Age'        =>  4,
        'Badge'      =>  8,
        'Verified'   =>  4,
        'Notes'      => 72,
        'Home City'  => 16,
        'USSF State' =>  4,
      //'AV Fri'     =>  8,
      //'AV Sat'     =>  8,
      //'AV Sun'     =>  8,
      //'LO Fri'     =>  6,
      //'LO Sat'     =>  6,
        'LO With'    =>  8,
        'TR From'    =>  8,
        'TR With'    =>  8,
        'Assess'     =>  8,
        'Upgrading'  =>  8,
        'Team Aff'   => 10,
        'Team Desc'  => 10,
        'Level'      => 14,
        'LE CR'      =>  6,
        'LE AR'      =>  6,
    );
    protected function writeHeaders($ws,$row,$headers)
    {
        $col = 0;
        foreach($headers as $header)
        {
            if (isset($this->widths[$header])) $width = $this->widths[$header];
            else                               $width = 16;
            
            $ws->getColumnDimensionByColumn($col)->setWidth($width);
            $ws->setCellValueByColumnAndRow($col,$row,$header);
            $col++;
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
