<?php

namespace Libraries;

/**
 * ----------------------------------------------------------
 * || Base Controller: Loads the Models and View ||
 * ----------------------------------------------------------
 */

class Controller
{
  // Load model
  protected $model;

  /**
   * Instantiate and return the required model 
   *
   * @param string $model - model name
   * @return Model object
   */
  public function model(string $model) 
  {
    // Require the model file
    require_once(MODEL_DIR . $model . '.php');

    // Instantiate the model
    return new $model();
  }

  /**
   * Checks if view file exists, if it does require the file, else stop the application
   *
   * @param string $view - view filename
   * @param array $data
   * @return void
   */
  public function view(string $view, array $data = [])
  {
    (file_exists(VIEWS_DIR . $view . '.html')) ? require_once(VIEWS_DIR . $view . '.html') : die('View does not exist');
  }
}