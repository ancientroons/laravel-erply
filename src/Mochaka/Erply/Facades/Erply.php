<?php 
namespace Mochaka\Erply\Facades;

use Illuminate\Support\Facades\Facade;

class Erply extends Facade {

  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return 'erply'; }

}