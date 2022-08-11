<?php

namespace Controllers;

use Libraries\Controller;

class Pages extends Controller
{
  public function __construct()
  {

  }

  /**
   * Default method if there is no method
   *
   * @return void
   */
  public function index() 
  {
    $data = [
      'title'       => 'Shape-Share Landing',
      'description' => 'Shape-Share is a simple online image sharing platform where members can upload, react, save, and search for images of shapes.'
    ];

    $this->view('static_pages/site_landing', $data);
  }

  public function aboutUs()
  {
    $data = [
      'title'       => 'About the Project',
      'description' => 'This personal project started as a way to learn front and backend website development and has evolved into a self-teaching tool I wish to share with the world.',
    ];

    $this->view('static_pages/about', $data);
  }
}