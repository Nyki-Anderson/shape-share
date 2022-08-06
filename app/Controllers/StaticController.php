<?php

namespace Controllers;

use Library\AbstractController;
use Library\View;

class StaticController extends AbstractController
{
  function indexAction()
  {
    View::render('static_pages/landing.html');
  }
}