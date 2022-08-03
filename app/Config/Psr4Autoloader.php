<?php

namespace Config;

/**
 * Resource: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md

 * General-purpose autoloader with optional functionality of allowing multiple base directories for a single name prefix.

 * Given shape-share as a package of classes in the file system at the following paths...

 *    /Users/nykianderson/Sites/shape-share/
 *        app/
 *            Controllers/
 *                Controller.php        # Shape\Share\Controllers\Controller
 *        tests/
 *            Test.php                  # Shape\Share\Test
 *            Database/
 *                DatabaseTest.php      # Shape\Share\Database\DatabaseTest

 * ... add the path to class files for \Shape\Share namespace prefix as follows:

 *    <?php
 *
 *    $loader = new \Library\Psr4AutoloaderClass;

 *    $loader->register();

 *    $loader->addNamespace('Shape\Share', '/Users/nykianderson/Sites/      shape-share/app');
 *    $loader->addNamespace('Shape\Share\, '/Users/nykianderson/Sites/shape-share/tests');

 * The following line would cause the autoloader to attempt to load the \Shape\Share\Controllers\Controller class from /Users/nykianderson/Sites/shape-share/app/Controllers/Controller.php

 *      <?php
 *      new \Shape\Share\Controllers\Controller;
 */
class Psr4Autoloader
{
  /**
   * An associative array where the key is a namepace prefix and the value is an array of base directories for classes in that namespace.

   * @var array
   */
  protected $prefixes = array();

  /**
   * Register loader with SPL autoloader stack
   *
   * @return void
   */
  public function register()
  {
    spl_autoload_register(array($this, 'loadClass'));
  }

  /**
   * Adds a base directory for a namespace prefix
   *
   * @param string $prefix - the namespace prefix
   * @param string $base_dir - a base directory for class files in the namespace
   * @param bool $prepend - if true, prepend the base directory to the stack instad of appending it; this causes it to be searched first rather than last
   *
   * @return void
   */
  public function addNamespace($prefix, $base_dir, $prepend = false)
  {
    // Normalize namespace prefix
    $prefix = trim($prefix, '\\') . '\\';

    // Normalize the base directory with a trailing separator
    $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';

    // Initialize the namespace prefix array
    if (isset($this->prefixes[$prefix]) === false) {

      $this->prefixes[$prefix] = array();
    }

    // Retain the base directory for the namespace prefix
    if ($prepend) {

      array_unshift($this->prefixes[$prefix], $base_dir);
    } else {

      array_push($this->prefixes[$prefix], $base_dir);
    }
  }

  /**
   * Loads the class file for a given class name.

   * @param string $class - the fqcn (fully-qualified class name).
   * @return mixed - the mapped file name on success, or boolean false on failure
   */
  public function loadClass($class)
  {
    // The current namespace prefix
    $prefix = $class;

    // Work backwards through the namespace names of the fqcn to find a mapped file name
    while (false !== $pos = strrpos($prefix, '\\')) {

      // Retain the trailing namespace separator in the prefix
      $prefix = substr($class, 0, $pos + 1);

      // The rest is the relative class name
      $relative_class = substr($class, $pos + 1);

      // Try to load a mapped file for the prefix and relative class
      $mapped_file = $this->loadMappedFile($prefix, $relative_class);

      if ($mapped_file) {

        return $mapped_file;
      }

      // Remove the trailing namepsace separator for the next iteration of strrpos()
      $prefix = rtrim($prefix, '\\');
    }

    // Never found a mapped file
    return false;
  }

  /**
   * Load the mapped file for a namespace prefix and relative class

   * @param string $prefix - the namespace prefix
   * @param string $relative_class - the relative class name
   * @return mixed Boolean - false if no mapped file can be loaded, or the name of the mapped file that was loaded
   */
  protected function loadMappedFile($prefix, $relative_class)
  {
    // Are there any base directories for this namespace prefix?
    if (isset($this->prefixes[$prefix]) === false) {

      return false;
    }

    // Look through base directories for this namespace prefix
    foreach ($this->prefixes[$prefix] as $base_dir) {

      // Replace the namespace prefix with the base directory, replace namespace separators with directory separators in the relative class name, and append with .php
      $file = $base_dir
        . str_replace('\\', '/', $relative_class)
        . '.php';

      // If the mapped file exists, require it
      if ($this->requireFile($file)) {

        // Yes, we're done
        return $file;
      }
    }
    // Never found it
    return false;
  }

  /**
   * If a file exists, require it from the file system

   * @param string $file - the file to require
   * @return bool - true if the file exists, false if not
   */
  protected function requireFile($file)
  {
    if (file_exists($file)) {

      require $file;

      return true;
    }
    return false;
  }
}