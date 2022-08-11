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

  /**
   * Compares a string to a regex patter that simulates any regex to determine if string is meant to be a regex pattern.
   *
   * @param string $string
   * @return boolean - true if is regex, false if (most likely) string
   */
  public static function isRegex(string $string)
  {
    $regex = "/^\/[\s\S]+\/$/";

    return preg_match($regex, $string);
  }

  /**
   * Detect Magic Quotes and Remove Then (remove backslashes)
   *
   * @param array||string $value
   * @return void
   */
  function stripSlashDeep($value) 
 {
  $value = is_array($value) ? array_map('stripSlashDeep', $value) : stripslashes($value);

  return $value;
 }

 
}