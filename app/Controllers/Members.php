<?php

namespace Controllers;

use Libraries\Controller;

class Members extends Controller 
{
  private $memberModel;

  public function __construct()
  {
    $this->memberModel = $this->model('Member');
  }

  public function index()
  {
    
  }
}