<?php
declare(strict_types=1);

namespace framework\Core;

use framework\Core\DependencyInjection\ContainerInterface;
use framework\Exception\ExceptionController;
use framework\Service\Logger\LoggerInterface;
use framework\Service\Profiler\ProfilerInterface;

use function Helpers\convertToCamelCase;
use function Helpers\convertToStudlyCaps;

class Core
{
  /** @var ContainerInterface */
  protected $container;

  /** @var LoggerInterface */
  protected $logger;

  /** @var string */
  protected $environment = ENVIRONMENT_VAR;

  /**
   * Core constructor
   *
   * @param string $environment
   * @param ContainerInterface $container
   * @throws \Framework\Exception\RuntimeException
   */
  public function __construct(string $environment, ContainerInterface $container)
  {
    $this->environment = $environment;
    $this->container = $container;
    $this->logger = $this->container->get(LoggerInterface::class);
  }

  public function init()
  {
    ini_set("error_log", "tmp/logs/error.log");
    ini_set("log_errors", "1");

    switch ($this->environment) {
      case 'dev':
        ini_set('display_errors', "1");

        /** @var ProfilerInterface $profiler */
        $profiler = $this->container->get(ProfilerInterface::class);
        
        $profiler->start('Core:init');
        break;

      case 'test':
          ini_set('display_errors', "1");
          break;

      case 'prod':
        ini_set('display_errors', "0");
        break;
    }

    $this->startDispatcher();

    switch ($this->environment) {
      case 'dev':
        $profiler = $this->container->get(ProfilerInterface::class);
        $profiler->end('Core:init');

        $render = true;
        $headers = headers_list();

        foreach($headers as $header) {

          if (stripos($header, "Content-type: application/json") !== false) {

            $render = false;
          }
        }
        if ($render) {

          print $profiler->render();
        }
        break;

      case 'test':
        break;

      case 'prod':
        break;
    }
  }

  protected function startDispatcher()
  {
    $controller = '';
    $action = '';
    $param = false;

    if (isset($_GET['action'])) {

      $action = $_GET['action'];
    }

    if (isset($_GET['controller'])) {

      $controller = $_GET['controller'];
    }

    if (isset($_GET['param'])) {

      $param = $_GET['param'];
    }

    try {
      $this->useController($controller, $action, $param);
    
    } catch (\Throwable $e) {

      $exceptionController = new ExceptionController();
      $exceptionController->setContainer($this->container);
      $exceptionController->setEnvironment($this->environment);

      print $exceptionController->render($e);

      $this->logger->critical('Fatal Error: ' . $e->getMessage(), $e->getTrace());
    }
  }

  public function redirect($url, $statusCode = 303)
  {
    header('Location ' . $url, true, $statusCode);
    die();
  }

  public static function dirNameFilter($name)
  {
    $name = str_replace('.', '', (string)$name);
    $name = str_replace('/', '', $name);
    return $name;
  }

  /**
   * @param string $controller
   * @param string $action
   * @param boolean $param
   * @return void
   */
  public function useController(string $controller = '', string $action = '', $param = false)
  {
    if (! $controller) {
      $controller = 'index';
    }

    if (! $action) {
      $action = 'index';
    }

    $controller = convertToStudlyCaps($controller) . "Controller";
    $action = convertToCamelCase($action);
    
    $function = $action;

    $controller = 'Controllers\\' . $controller;

    if (class_exists($controller)) {

      $c = $this->container->get($controller);

      if ($c instanceof Controller) {

        $c->setContainer($this->container);
        $c->setEnvironment($this->environment);
      }

      print call_user_func_array(array($c, $function), array($param));
    
    } else {

      throw new \Exception(('Controller class not found.'));
    }
  }

  /**
   * @param string $view
   * @param array $data
   * @return void
   */
  private static function load(string $view, array $data = [])
  {
    extract(array("content" => $data));
    ob_start();

    require(VIEW_PATH . "templates/$view.php");

    $content = ob_get_clean();

    return (string)$content;
  }

  public static function loadView(string $view, array $data = [], bool $returnOnlyContent = false)
  {
    if ($returnOnlyContent) {

      return self::load($view, $data);
    }

    extract(array("contentAll" => self::load($view, $data)));
    ob_start();

    require(VIEW_PATH . "layout/template.php");

    $content = ob_get_clean();

    return (string)$content;
  }
}