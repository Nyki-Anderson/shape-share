<?php
declare(strict_types=1);

namespace Security;

use Exception\RuntimeException;

interface PasswordManagerInterface
{
  /**
   * @param string $plainPassword
   * @return string
   * @throws RuntimeException
   */
  public function generatePasswordHash(string $plainPassword): string;

  /**
   * @param string $plainPassword
   * @param string $hashPassword
   * @return boolean
   */
  public function isPasswordValid(string $plainPassword, string $hashPassword): bool;
}