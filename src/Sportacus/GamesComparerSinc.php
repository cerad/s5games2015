<?php
namespace Cerad\Component\Sportacus;

class GamesComparerSinc
{
  protected $sporGames;
  protected $sincGames;
  protected $results = [];
  protected $map = [
    'fields' => [
      'Merrimack 1N' => 'Merrimack 1 North',
      'Merrimack 1S' => 'Merrimack 1 South',
      'Merrimack 2'  => 'Merrimack #2',
      'Merrimack 4'  => 'Merrimack #4',
      'Merrimack 5'  => 'Merrimack #5',
      'Merrimack 9'  => 'Merrimack #9',
      'Merrimack 10' => 'Merrimack #10',
      'JH1'          => 'John Hunt #1',
      'JH2'          => 'John Hunt #2',
    ],
    'teams' => [
      'U10F01' => [
        'Area 5B Wildcats'     => 'A005B-SU10G-01-Stratton',
        'R498 Madison Maniacs' => 'R0498-SU10G-01-Mullins',
        'R773 U10G Watson'     => 'R0773-SU10G-01-Watson',
        'R124 Cherry Bombs'    => 'R0124-SU10G-01-Pittman',
        'R605 Galaxy'          => 'R0605-SU10G-01-Strom',
        'R605 Eclipse'         => 'R0605-SU10G-02-Nguyen',
        
        'Group A #1' => 'U10G-Group A #1',
        'Group A #2' => 'U10G-Group A #2',
        'Group A #3' => 'U10G-Group A #3',
        'Group B #1' => 'U10G-Group B #1',
        'Group B #2' => 'U10G-Group B #2',
        'Group B #3' => 'U10G-Group B #3',
        
        'Winner of Round 4 Game 1' => 'U10G-Winner of Round 4 Game 1',
        'Winner of Round 4 Game 2' => 'U10G-Winner of Round 4 Game 2',
      ],
      'U10M01' => [
        'R498 Madison Thunder'   => 'R0498-SU10B-01-Woods',
        'R1174 Freeman'          => 'R1174-SU10B-01-Freeman',
        'R124 Cobras'            => 'R0124-SU10B-01-Daley',
        'R1459 AVENGERS'         => 'R1459-SU10B-01-Housen',
        'R498 Madison Lightning' => 'R0498-SU10B-02-Terrell',
        'R890 Interceptors'      => 'R0890-SU10B-01-Wakham',
        'R773 Tigers'            => 'R0773-SU10B-01-Barnett',
        
        'Group A #1' => 'U10B-Group A #1',
        'Group A #2' => 'U10B-Group A #2',
        'Group A #3' => 'U10B-Group A #3',
        'Group A #4' => 'U10B-Group A #4',
        'Group A #5' => 'U10B-Group A #5',
        'Group A #6' => 'U10B-Group A #6',
        'Group A #7' => 'U10B-Group A #7',
        
        'Winner of Round 5 Game 2' => 'U10B-Winner of Round 5 Game 2',
        'Winner of Round 6 Game 1' => 'U10B-Winner of Round 6 Game 1',
        'Winner of Round 6 Game 2' => 'U10B-Winner of Round 6 Game 2',
      ],
      'U12F01' => [
        'R773 Strikers' => 'R0773-SU12G-01-Edmonson',
        'R551 U12G'     => 'R0551-SU12G-01-Mason',
        'R605 Astros'   => 'R0605-SU12G-01-Brady',
        'R160 Storm'    => 'R0160-SU12G-01-Criswell',
        'R605 Comets'   => 'R0605-SU12G-02-Jones',
        'R498 Select'   => 'R0498-SU12G-01-Rossetti',
        
        'Group A #1' => 'U12G-Group A #1',
        'Group A #2' => 'U12G-Group A #2',
        'Group A #3' => 'U12G-Group A #3',
        'Group B #1' => 'U12G-Group B #1',
        'Group B #2' => 'U12G-Group B #2',
        'Group B #3' => 'U12G-Group B #3',
        'Winner of Round 4 Game 1' => 'U12G-Winner of Round 4 Game 1',
        'Winner of Round 4 Game 2' => 'U12G-Winner of Round 4 Game 2',
      ],
      'U12M01' => [
        'R773 Raptors'    => 'R0773-SU12B-01-Gargus',
        'R498 Raptors'    => 'R0498-SU12B-01-King',
        'R124 Elite'      => 'R0124-SU12B-01-Smith',
        'R1459 Fusion FC' => 'R1459-SU12B-01-Catron',
        'R605 Mambas'     => 'R0605-SU12B-01-Adamson',
        'R160 United'     => 'R0160-SU12B-02-Brazier',
        'R498 Boomerangs' => 'R0498-SU12B-02-Derby',
        'R160 Rockets'    => 'R0160-SU12B-01-Brinkley',
        'R722 U12 Boys'   => 'R0722-SU12B-01-Bertha',
        
        'Wildcard #1' => 'U12B-Wildcard #1',
        'Wildcard #2' => 'U12B-Wildcard #2',
        'Wildcard #3' => 'U12B-Wildcard #3',
        'Wildcard #4' => 'U12B-Wildcard #4',
        'Wildcard #5' => 'U12B-Wildcard #5',
        'Wildcard #6' => 'U12B-Wildcard #6',
        'Wildcard #7' => 'U12B-Wildcard #7',
        'Wildcard #8' => 'U12B-Wildcard #8',
        'Wildcard #9' => 'U12B-Wildcard #9',
        
        'Winner of Game #118' => 'U12B-Winner of Game #118',
        'Winner of Game #119' => 'U12B-Winner of Game #119',
        'Winner of Game #120' => 'U12B-Winner of Game #120',
        'Winner of Game #121' => 'U12B-Winner of Game #121',
        'Winner of Game #122' => 'U12B-Winner of Game #122',
        'Winner of Game #123' => 'U12B-Winner of Game #123',
        'Winner of Game #124' => 'U12B-Winner of Game #124',
        'Winner of Game #125' => 'U12B-Winner of Game #125',
        
        'Loser of Game #118'  => 'U12B-Loser of Game #118',
      //'Loser of Game #119'  => 'U12B-Loser of Game #119',
        'Loser of Game #120'  => 'U12B-Loser of Game #120',
        'Loser of Game #122'  => 'U12B-Loser of Game #122',
        'Loser of Game #123'  => 'U12B-Loser of Game #123',
      //'Loser of Game #124'  => 'U12B-Loser of Game #124',
      ],
      'U14F01' => [
        'R1174 Rhoades'   => 'R1174-SU14G-01-Rhoades',
        'R498 Sutherland' => 'R0498-SU14G-01-Sutherland',
        'R498 Ford'       => 'R0498-SU14G-02-Ford',
        'R551 U14G'       => 'R0551-SU14G-01-Barnwell',
        'R1159 Lynx'      => 'R1159-SU14G-01-Kemp',
      ],
      'U14M01' => [
        'R160 International FC' => 'R0160-SU14B-01-Freeman',
        'R1011 MC Hammers'      => 'R1011-SU14B-01-Glasgow',
        'R914 East Limestone'   => 'R0914-SU14B-01-Lunsford',
        'R124-Olimpico'         => 'R0124-SU14B-01-Hendrickson',
        'R1603 BC UNITED'       => 'R1603-SU14B-01-Tenorio',
        'R498 Select - MCFC'    => 'R0498-SU14B-01-Worcester',
        'R894 Monrovia DYNAMO'  => 'R0894-SU14B-01-Griswell',
        
        'Group A #1' => 'U14B-Group A #1',
        'Group A #2' => 'U14B-Group A #2',
        'Group A #3' => 'U14B-Group A #3',
        'Group A #4' => 'U14B-Group A #4',
        'Group A #5' => 'U14B-Group A #5',
        'Group A #6' => 'U14B-Group A #6',
        'Group A #7' => 'U14B-Group A #7',
        
        'Winner of Round 4 Game 1' => 'U14B-Winner of Round 4 Game 1',
        'Winner of Round 5 Game 1' => 'U14B-Winner of Round 5 Game 1',
        'Winner of Round 5 Game 2' => 'U14B-Winner of Round 5 Game 2',
      ],
      'U19F01' => [
        'R551 U19G'           => 'R0551-SU19G-01-Kimbrough',
        'R605-Phoenix'        => 'R0605-SU19G-01-Riley',
        'R498 Madison Minion' => 'R0498-SU19G-01-Mason',
        'R498 The Select Few' => 'R0498-SU19G-02-Cutshall',
        'R160 Lady Mavericks' => 'R0160-SU19G-01-Phonthibsvads',
      ],
      'U19M01' => [
        'R498 Select'       => 'R0498-SU19B-01-Draper',
        'R160 Nuggets'      => 'R0160-SU19B-01-Lloyd',
        'R605 Viking-Angel' => 'R0605-SU19B-01-Martin',
        'R160 Mavericks'    => 'R0160-SU19B-02-Phonthibsvads',
        'R498 Mayhem'       => 'R0498-SU19B-02-Grove',
      ],
    ],
  ];
  protected function compareGame($sporGame,$sincGame)
  {
    $num = (integer)$sporGame['num'];
    
    if (!$sincGame) {
      $results[$num] = [
        'problems'  => ['Not in cinc games'],
        'spor_game' => $sporGame,
      ];
      return;
    }
    if (strcmp($sporGame['date'],$sincGame['date'])) {
      die('date mismatch');
    }
    if (strcmp($sporGame['time'],$sincGame['time'])) {
      die('time mismatch');
    }
    $sporDivName = $sporGame['divName'];
    $sporHomeTeamName = $sporGame['teams']['home']['name'];
    $pos = strpos($sporHomeTeamName,$sporDivName);
    if ($pos !== false) {
      $sporGender = substr($sporHomeTeamName,$pos+3,1);
      switch($sporGender) {
        case 'B': $sporDivName .= 'M'; break;
        case 'G': $sporDivName .= 'F'; break;
      }
    }
    if (strpos($sincGame['div'],$sporDivName) !== 0)
    {
      echo sprintf("Game %s Div Spor %s %s Sinc %s\n",
        $num,$sporGame['divName'],$sporDivName,$sincGame['div']);
      die('div mismatch');
    }
    $sporFieldName = $sporGame['fieldName'];
    $sincFieldName = $sincGame['field'];
    
    $sporFieldNameMapped = isset($this->map['fields'][$sporFieldName]) ? $this->map['fields'][$sporFieldName] : $sporFieldName;
    if (strcmp($sporFieldNameMapped,$sincFieldName))
    {
      echo sprintf("Game %s Field Spor %s Sinc %s\n",
        $num,$sporFieldNameMapped,$sincFieldName);
      die('field mismatch');
      
    }
    foreach(['home','away'] as $role)
    {
      $sporTeamName = $sporGame['teams'][$role]['name'];
      $sincTeamName = $sincGame['teams'][$role]['name'];
      $sincTeamDiv  = $sincGame['div'];
      $sincTeamNameMapped = 
        isset($this->map['teams'][$sincTeamDiv][$sincTeamName]) ? 
              $this->map['teams'][$sincTeamDiv][$sincTeamName] :
              $sincTeamName;
      
      if (strcmp($sporTeamName,$sincTeamNameMapped)) {
        echo sprintf("Game %s %s %s Team Spor %s Sinc %s %s %s\n",
          $num,$sporDivName, $role,$sporTeamName,$sincTeamDiv,$sincTeamName,$sincTeamNameMapped);
        die('team mismatch');
      }
    }
  }
  public function compare($sporGames, $sincGames)
  {
    $this->sporGames = $sporGames;
    $this->sincGames = $sincGames;
    
    foreach($sporGames as $sporGame)
    {
      $num = (integer)$sporGame['num'];
      
      $sincGame = isset($sincGames[$num]) ? $sincGames[$num] : null;
      
      $this->compareGame($sporGame,$sincGame);
    }
    return $this->results;
  }
}