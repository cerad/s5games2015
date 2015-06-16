<?php
namespace Cerad\S5Games;

use Cerad\Component\Excel\Loader as BaseLoader;

class LoadTeamNames extends BaseLoader
{
  protected $record = [
    'sinc'       => ['cols' => 'Sync'],
    'sportacus'  => ['cols' => 'Sportacus'],
  ];
  protected function processItem($item)
  {
    $this->items[$item['sinc']] = $item['sportacus'];
    //print_r($item); die;
  }
}