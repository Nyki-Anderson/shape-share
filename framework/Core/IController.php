<?php
declare(strict_types=1);

namespace Core;

interface IController
{
  public function renderView(string $viewName, array $variables = [], string $contentType = null, bool $returnOnlyContent = false, int $status = 200);

  public function isAuth();

  public function setAuth(string $login);

  public function destroyAuth();
}