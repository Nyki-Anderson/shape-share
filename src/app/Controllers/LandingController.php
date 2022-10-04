<?php

namespace Controllers;

use Core\Controller;
use Libraries\Validation; 
use Models\Member;

class LandingController extends Controller
{
  private $memberModel;

  public function __construct()
  {
    parent::__construct();
    #$this->memberModel = new Member();
  }

  public function index()
  {
    $data = [
      'title' => 'Welcome to Shape-Share!',
      'description' => 'Shape-Share is a simple online image sharing platform where members can upload, react, save, and search for images of shapes.',
    ];
    return $this->renderView('static_pages' . DS . 'site_landing', $data);
  }

  public function register()
  {
    if (! empty($_POST) && $_SERVER["REQUEST_METHOD"] == "POST")
    {
      $formInput = [
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'passConfirm' => $_POST['passConfirm'],
        'aboutMe' => $_POST['aboutMe'],
        'month' => $_POST['month'],
        'day' => $_POST['day'],
        'year' => $_POST['year'],
        'gender' => $_POST['gender'],
        'occupation' => $_POST['occupation'],
        'hometown' => $_POST['hometown'],
        'country' => $_POST['country'],
        'favShape' => $_POST['fav_shape'],
        'favColor' => $_POST['fav_color'],
        'modified' => null,
      ];

      $rules = [
        'username' => [
          'required'  => true,
          'pattern'   => 'username',
          'min'       => 5,
          'max'       => 25,
          'noCurse'   => true,
        ],
        'password'  => [
          'required'  => true,
          'pattern'   => 'password',
          'match'     => 'passConfirm',
          'min'       => 8,
          'max'       => 25,
        ],
        'aboutMe' => [
          'required'  => false,
          'pattern'   => 'alphanumchar',
          'max'       => 200,
          'noCurse'   => true,
        ],
        'year' => [
          'required'  => false,
          'date'      => true,
        ],
        'occupation' => [
          'required'  => false,
          'pattern'   => 'alphanumspace',
          'noCurse'   => true,
        ],
        'hometown' => [
          'required'  => false,
          'pattern'   => 'alphanumspace',
          'noCurse'   => true,
        ],
      ];

      $val = new Validation($formInput, $rules);
      $val->validate();

      if ($val->isValid())
      {

        $escInput = escapeUserInput($formInput);

        if ($this->memberModel->addMember($escInput)) {

          $data = [
            'title' => 'Successful Registration',
            'description' => 'Member has successfully registered for Shape-Share.',
            'success' => "Success! You are now a member of Shape-Share. You may now login."
          ];

          include(VIEW_PATH . 'forms' . DS . 'login.html');
        }

      } else {

        $data = [
          'title' => 'Error in Form',
          'description' => 'Form has at least one input error.',
          'errors' => $val->errors(),
        ];

        include(VIEW_PATH . 'forms' . DS . 'registration.html');
      }
    
    } else {

      $data = [
        'title' => 'Registration Form',
        'description' => 'A form to register for a free Shape-Share membership.',
      ];
  
      include(VIEW_PATH . 'forms' . DS . 'registration.html');
    }
  }

  public function login()
  {
    $data = [
      'title' => 'Login Form',
      'description' => 'A form to login to the membership dashboard',
    ];

    include(VIEW_PATH . 'forms' . DS . 'login.html');
  }
}