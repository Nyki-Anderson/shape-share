<?php

namespace Library;

/**
 * Base Controller
 */
abstract class AbstractController
{
  // Parameters from matched route
  protected $routeParams = [];

  public function __construct(array $routeParams)
  {
    $this->routeParams = $routeParams;
  }

  /**
   * Calls controller method and passes arguments.
   *
   * @param string $name - action name 
   * @param array $args - arguments passed to method
   * @return void
   */
  public function __call(string $name, array $args)
  {
    $method = $name . 'Action';

    if (method_exists($this, $method)) {

      if ($this->before() != false) {

        call_user_func_array([$this, $method], $args);
        
        $this->after();
      }
    
    } else {

      throw new \Exception('Method $method not found in controller', get_class($this));
    }
  }

  /**
   * Before filter - called before an action method.
   *
   * @return void
   */
  protected function before()
  {

  }

  /**
   * After filter - called after an action method.
   *
   * @return void
   */
  protected function after()
  {

  }
}