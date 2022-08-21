<?php

namespace Controllers;

use Core\Controller;

use function app\Helpers\escapeUserInput;
use function app\Helpers\verifyPasswordsMatch;

class IndexController extends Controller
{
  public function register()
  {
    if (! empty($_POST) && $_SERVER["REQUEST_METHOD"] == "POST")
    {
      $formInput = [
        'username' => $_POST('username'),
        'password' => $_POST('password'),
        'passConfirm' => $_POST('passConfirm'),
        'aboutMe' => $_POST('about_me'),
        'bdayMonth' => $_POST('bday_month'),
        'bdayDay' => $_POST('bday_day'),
        'bdayYear' => $_POST('bday_year'),
        'gender' => $_POST('gender'),
        'occupation' => $_POST('occupation'),
        'hometown' => $_POST('hometown'),
        'country' => $_POST('country'),
        'favShape' => $_POST('fav_shape'),
        'favColor' => $_POST('fav_color'),
      ];

      $escapedInput = escapeUserInput($formInput);

      if (verifyPasswordsMatch($escapedInput['password'], $escapedInput['passConfirm']))
      {

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