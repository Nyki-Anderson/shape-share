<?php
declare(strict_types=1);

namespace Core;

use DependencyInjection\ContainerInterface;
use Service\Logger\LoggerInterface;

class Core
{
  /** @var ContainerInterface */
  protected $container;

  /** @var LoggerInterface */
  protected $logger;

  /** @var string */
  protected $environment;

  /**
   * Core constructor
   *
   * @param string $environment
   * @param ContainerInterface $container
   * @throws \Framework\Exception\RuntimeException
   */
  public function __construct(ContainerInterface $container)
  {
    $this->environment = ENVIRONMENT;
    $this->container = $container;
    $this->logger = $this->container->get(LoggerInterface::class);
  }

  public function init()
  {
    
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

    require(VIEW_PATH . "{$view}.html");

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

    #require(VIEW_PATH . "layout/template.php");

    $content = ob_get_clean();

    return (string)$content;
  }
}