<?php
declare(strict_types=1);

namespace framework\Security;

use framework\Exception\RuntimeException;

class PasswordManager implements PasswordManagerInterface
{
  /**
   * @param string $plainPassword
   * @return string
   * @throws RuntimeException
   */
  public function generatePasswordHash(string $plainPassword): string
  {
   $hash = password_hash($plainPassword, PASSWORD_BCRYPT);
   
    if (! is_string($hash)) {

      throw new RuntimeException('Failure to create a password hash');
    }

    return $hash;
  }

  public function isPasswordValid(string $plainPassword, string $hashPassword): bool
  {
    return password_verify($plainPassword, $hashPassword);
  }
}