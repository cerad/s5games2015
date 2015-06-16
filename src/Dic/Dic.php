<?php
namespace Cerad\Component\Dic;

use Pimple \Container as PimpleDic;
use Interop\Container\ContainerInterface as DicInterface;

class Dic extends PimpleDic implements DicInterface
{
  /**
   * {@inheritdoc}
   * 
   * TODO: Add interop exception
   */
  public function get($id)
  {
    return $this->offsetGet($id);
  }
  public function has($id)
  {
    return $this->offsetExists($id);
  }
}