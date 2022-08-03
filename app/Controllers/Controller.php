<?php

namespace Controllers;

abstract class Controller
{
  // Stored data retrieved from model
  protected $data = array();

  // View"s name to be rendered.
  protected $view = "";

  // Website"s HTML head: title and description
  protected $head = array("title" => "", "description" => "");

  // Each controller will implement own process method so it is marked as abstract.
  abstract function process($params);

  // Render view to user, if view is specified we"ll require it.
  public function renderView()
  {
    if ($this->view) {

      // Extract variables from $data array making them available as ordinary variables in template
      extract($this->data);
      require("views/" . $this->view . ".phtml");
    }
  }

  // Redirection method to send user to another page and terminate current script.
  public function redirect($url)
  {
    header("Location: /$url");
    header("Connection: close");
    exit;
  }
}