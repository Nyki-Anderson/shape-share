<?php
declare(strict_types=1);

namespace framework\Core\DependencyInjection;

use framework\Exception\RuntimeException;
use framework\Security\PasswordManager;
use framework\Security\PasswordManagerInterface;
use framework\Service\Logger\BasicLogger;
use framework\Service\Logger\LoggerInterface;

class Container implements ContainerInterface
{
  /** @var array */
  protected $services = [];

  /**
   * Container constructor
   * @throws RuntimeException
   */
  public function __construct()
  {
    $this->services[PasswordManagerInterface::class] = $this->get(PasswordManager::class);

    $this->services[LoggerInterface::class] = $this->get(BasicLogger::class);
  }

  /**
   * @param string $id
   * @param object $service
   * @return void
   */
  public function set(string $id, object $service): void
  {
    $this->services[$id] = $service;
  }

  public function get(string $className): object
  {
    if (isset($this->services[$className])) {

      return $this->services[$className];
    }

    try {
      $reflector = new \ReflectionClass($className);

      if ($reflector->isInstantiable()) {

        // Get class constructor
        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {

          // Get new instance from class
          $instance = $reflector->newInstance();
          $this->set($className, $instance);

          return $instance;
       
        } else {

          $parameters = $constructor->getParameters();
          $dependencies = [];

          foreach ($parameters as $parameter) {

            $dependency = $parameter->getClass();
            $dependencies[] = $this->get($dependency->name);
          }

          $instance = $reflector->newInstanceArgs($dependencies);
          $this->set($className, $instance);

          return $instance;
        }
      
      } elseif ($reflector->isInterface()) {

        $classNameImpl = preg_replace('/Interface$/', '', $className);

        if (class_exists($classNameImpl)) {

          return $this->get($classNameImpl);
        
        } else {

          throw new RuntimeException("Interface implementation {$className} does not exist in container.");
        }
      }
    } catch (\Throwable $exception) {

      throw new RuntimeException($exception->getMessage());
    }

    throw new RuntimeException("Class or service {$className} does not exist in container.");
  }
}