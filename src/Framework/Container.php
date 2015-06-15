<?php
namespace Cerad\Component\Framework;

use Pimple \Container as PimpleContainer;
use Interop\Container\ContainerInterface as InteropContainerInterface;

class Container extends PimpleContainer implements InteropContainerInterface
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