<?php
declare(strict_types=1);

namespace framework\ValueObject;

interface IValueObject
{
  /**
   * Compare two IValueObjects and tells whether they can be considered equal
   *
   * @param IValueObject $object
   * @return boolean
   */
  public function equals(IValueObject $object): bool;

  /**
   * Returns a string representation of the object
   *
   * @return string
   */
  public function __toString(): string;
}