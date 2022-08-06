<?php

namespace Helpers;

class StringFormatHelper 
{
  /**
   * Convert $string to StudlyCaps.
   *
   * @param string $string - string-to-convert
   * @return string $string - StringToConvert
   */
  public static function convertToStudlyCaps(string $string)
  {
    // Replace hypens with spaces
    $string = str_replace('-', ' ', $string);

    // Capitalize first letter after each space
    $string = ucwords($string);

    // Replace each space with no space
    $string = str_replace(' ', '', $string);

    return $string;
  }

   /**
   * Convert $string to camelCase 
   *
   * @param string $string - string-to-convert
   * @return string $string - stringToConvert
   */
  public static function convertToCamelCase(string $string)
  {
    // Replace hypens with spaces
    $string = str_replace('-', ' ', $string);

    // Capitalize first letter after each space
    $string = ucwords($string);

    // Replace all spaces with no space
    $string = str_replace(' ', '', $string);

    // Make first letter lowercase
    $string = lcfirst($string);

    return $string;
  }
}