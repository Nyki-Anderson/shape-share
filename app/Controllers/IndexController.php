<?php

namespace Controllers;

use Core\Controller;
use Libraries\Validation;
use app\Helpers\validation_helper;

class IndexController extends Controller
{
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
      ];

      $escInput = escapeUserInput($formInput);

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
          'match'     => $escInput['passConfirm'],
          'min'       => 8,
          'max'       => 25,
        ],
        'aboutMe' => [
          'pattern'   => 'alphanumchar',
          'max'       => 200,
          'noCurse'   => true,
        ],
        'year' => [
          'date'      => true,
        ],
        'occupation' => [
          'pattern'   => 'alphanumspace',
          'noCurse'   => true,
        ],
        'hometown' => [
          'pattern'   => 'alphanumspace',
          'noCurse'   => true,
        ],
      ];

      $val = new Validation($escInput, $rules);
      $val->validate();

      if ($val->isValid())
      {


      } else {

        $data = [
          'title' => 'Error in Form',
          'description' => 'Form has at least one input error.',
          'errorMsgs' => $val->errors(),
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