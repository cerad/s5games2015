<?php
namespace Cerad\S5Games;

class TranSincSporGames
{
  protected $fields = [
    'John Hunt #1'  => 'JH1',
    'John Hunt #2'  => 'JH2',
    'Merrimack #1'  => 'Merrimack 1N',
    'Merrimack #10' => 'Merrimack 10',
    'Merrimack #2'  => 'Merrimack 2',
    'Merrimack #4'  => 'Merrimack 4',
    'Merrimack #5'  => 'Merrimack 5',
    'Merrimack #7'  => 'Merrimack 1S',
    'Merrimack #9'  => 'Merrimack 9',
  ];
  protected $divs = [
    'U10F01' => 'U10', 'U10M01' => 'U10', 
    'U12F01' => 'U12', 'U12M01' => 'U12', 
    'U14F01' => 'U14', 'U14M01' => 'U14', 
    'U16F01' => 'U16', 'U16M01' => 'U16', 
    'U19F01' => 'U19', 'U19M01' => 'U19',
  ];
  public function transform($sincGames,$teamNames)
  {
    $divs   = $this->divs;
    $fields = $this->fields;
    
    $sporGames = [];
    foreach($sincGames as $game) {
      $game['divSinc'] = $game['div'];
      $game['div']     = $divs[$game['div']];
      
      $game['fieldSinc'] = $game['field'];
      $game['field']     = $fields[$game['field']];
      
      $homeTeamName = $game['teams']['home']['name'];
      $awayTeamName = $game['teams']['away']['name'];
      
      $game['teams']['home']['nameSinc'] = $homeTeamName;
      $game['teams']['away']['nameSinc'] = $awayTeamName;
      
      $game['teams']['home']['name'] = $teamNames[$homeTeamName];
      $game['teams']['away']['name'] = $teamNames[$awayTeamName];
      
      $sporGames[] = $game;
    }
    return $sporGames;
  }
}