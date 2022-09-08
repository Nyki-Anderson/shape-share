<?php
declare(strict_types=1);

namespace framework\Core;

use framework\Core\DependencyInjection\ContainerInterface;
use framework\Exception\NotFoundException;
use framework\Exception\RuntimeException;
use framework\Response\Response;
use framework\Service\Auth\Auth;
use framework\Service\Cache\Cache;

/**
 * Class Controller
 */
class Controller implements IController
{
  /** @var ContainerInterface */
  private $container;

  /** @var string */
  private $environment;

  public function __construct()
  {
    $this->includeHelpers(['string_format']);
  }

  public function includeHelpers(array $helpers)
  {
    foreach ($helpers as $helper) {

      require_once(HELPER_PATH . "{$helper}_helper.php");
    }
  }

  /**
   * @param ContainerInterface $container
   * @return void
   */
  public function setContainer(ContainerInterface $container): void
  {
    $this->container = $container;
  }

  /**
   * @return ContainerInterface
   */
  public function getContainer(): ContainerInterface
  {
    return $this->container;
  }

  /**
   * @param string $environmemt
   * @return void
   */
  public function setEnvironment(string $environment): void
  {
    $this->environment = $environment;
  }

  /**
   * @return Cache
   */
  public function getCache(): Cache
  {
    /** @var Cache $instance */
    $instance = $this->container->get(Cache::class);
    return $instance;
  }

  /**
   * @return Auth
   */
  public function getAuth(): Auth
  {
    /** @var Auth $instance */
    $instance = $this->container->get(Auth::class);
    return $instance;
  }

  /**
   * @param $name
   * @param $argments
   * @return void
   */
  public function __call($name, $argments)
  {
    throw new NotFoundException();
  }

  /**
   * @param string $viewName
   * @param array $variables
   * @param string|null $contentType
   * @param boolean $returnOnlyContent
   * @param integer $status
   * @return void
   */
  public function renderView(string $viewName, array $variables = [], string $contentType = null, bool $returnOnlyContent = false, int $status = 200)
  {
    $body = Core::loadView($viewName, $variables, $returnOnlyContent);
    return new Response($body, $status, $contentType);
  }

  /** 
   * @return bool
   * @throws RuntimeException
   */
  public function isAuth()
  {
    return $this->getAuth()->isAuth();
  }

  /** 
   * @param string $login
   * @return bool
   * @throws RuntimeException
   */
  public function setAuth(string $login)
  {
    return $this->getAuth()->setAuth($login);
  }

  /** 
   * @throws $RuntimeException
   */
  public function destroyAuth()
  {
    $this->getAuth()->destroyAuth();
  }
}