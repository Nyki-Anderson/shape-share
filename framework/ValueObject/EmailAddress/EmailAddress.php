<?php
declare(strict_types=1);

namespace ValueObject\EmailAddress;

use Exception\InvalidArgumentException;
use ValueObject\IValueObject;

class EmailAddress implements IValueObject
{
  private $address;

  /**
   * EmailAddress constructor
   *
   * @param string $address
   * @throws InvalidArgumentException
   */
  public function __construct(string $address)
  {
    if (! filter_var($address, FILTER_VALIDATE_EMAIL)) {

      throw new InvalidArgumentException(sprintf('"%s" is not a valid email', $address));
    }

    $this->address = $address;
  }

  public function equals(IValueObject $object): bool
  {
    return strtolower((string)$this) === strtolower((string)$object);
  }

  public function __toString(): string
  {
    return $this->address;
  }
}