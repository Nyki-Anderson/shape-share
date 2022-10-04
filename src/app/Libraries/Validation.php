<?php

namespace Libraries;

/**
 * ------------------------------------------------------------
 * Validation for user input forms
 * ------------------------------------------------------------
 *
 *    Rule        |  Param       |    Description
 * -------------------------------------------------------------
 *  required        bool            Field required          
 *  email           bool            Validates an email
 *  match           field           Field must match given field
 *  pattern         regex           Field must match regex
 *  min             int             Value must be atleast
 *  max             int             Value must be no more than
 *  exact           int             Value must be exact
 *  noCurse         bool            Contains no curse words
 *  date            bool            Is a valid date
 *  file            bool            Is a valid file
 */
class Validation 
{
  /**
   * Array of regex values to be used with pattern function
   *
   * @var array - regex values for various inputs
   */
  public $regex = [
    'digit'         => '/^[\d]*$/',
    'alpha'         => '/^[a-zA-Z]*$/',
    'alphanum'      => '/^[a-zA-Z\d]*$/',
    'alphanumspace' => '/^[a-zA-Z\d ]*$/',
    'alphanumchar'  => '/^[\w\d !@#$%&$.,?:;-_]*$/',
    'username'      => '/^[a-zA-Z][a-zA-Z0-9-_]*$/',
    'password'      => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*0-9)(?=.*[#$@!%&*?])[A-Za-z0-9#$@!%&*?]$/',
  ];
  
  /**
   * Holds posted data (make sure to sanitize all data before passing to validation class).
   *
   * @var array
   */
  private $_post;

  /**
   * Holds uploaded file data
   *
   * @var file
   */
  private $_file;

  /**
   * A multidimensional array containing validation rules for all the fields
   *
   * @var array
   */
  private $_validationRules;

  /**
   * Holds all the errors for the fields
   *
   * @var array
   */
  private $_validationErrors;

  /**
   * If the form data contains no errors this will be true, else it will be false.
   *
   * @var boolean
   */
  private $_isValid = false;

  /**
   * Sets $_POST, $_FILE, and an array of validation rules to private instances and 
   *
   * @param array $post - array of field and value from form
   * @param array $rules - array of rules for each field 
   * @param array|null $file - image file or null if not set
   */
  public function __construct(array $post, array $rules, array $file = null)
  {
    $this->_post = $post;
    $this->_file = $file;
    $this->_validationRules = $rules;
  }

  /**
   * Iterates through each field with validation rules set and returns an array of error messages. 
   *
   * @return object $this - validation object
   */
  public function validate()
  {
    foreach ($this->_validationRules as $field => $rules)
    {
      $value = $this->_post[$field];
      
      foreach ($rules as $rule => $param)
      {
        if (! empty($value) && $rule != 'required') {
          switch ($rule)
          {
            case 'required':
              if (empty($value) && $param === true)
              {
                $this->addError("<b>{$field}</b> is required.");
              
              } elseif ($param === false) {

                break;
              }
              break;
            
            case 'email':
              if (! filter_var($value, FILTER_VALIDATE_EMAIL))
              {
                $this->addError("<b>{$field}</b> is not a valid email.");
              }
              break;

            case 'match':
              if (is_string($value))
              {
                if (strcasecmp($value, $this->_post[$param]) != 0)
                {
                  $this->addError("<b>{$field}</b> must match.");
                }
          
              } else {

                if ($value !== $this->_post[$param])
                {
                  $this->addError("<b>{$field}</b> must match <b>{$param}</b>.");
                }
              }
              break;
            
            case 'pattern':
              if (preg_match($this->regex[$param], $value) === false)
              {
                $this->addError("<b>{$field}</b> contains invalid characters. ", $param);
              }
              break;
            
            case 'min':
              if (strlen($value) < $param)
              {
                $this->addError("<b>{$field}</b> must be at least {$param} characters in length.");
              }
              break;
            
            case 'max':
              if (strlen($value) > $param)
              {
                $this->addError("<b>{$field}</b> must be less than {$param} characters in length.");
              }
              break;

            case 'exact':
              if (strlen($value) != $param)
              {
                $this->addError("<b>{$field}</b> must be exactly {$param} characters in length.");
              }
              break;

            case 'noCurse':
              if (! $this->curseWordsFilter($value))
              {
                $this->addError("<b>{$field}</b> contains language which may be inappropriate for some members.");
              }
              break;
            
            case 'date':
                if (! $this->validDate())
                {
                  $this->addError("This is not a valid date.");
                }
              break;
            
            case 'file':
              if (! $this->validFile($field))
              {
                $this->addError("{$field} is not valid.");
              }
              break;
          }
        }
      }
    }

    if (empty($this->_validationErrors))
    {
      $this->_isValid = true;
    }

    return $this;
  }

  private function validDate()
  {
    if (checkdate((int) $this->_post['month'], (int) $this->_post['day'], (int) $this->_post['year'])) {
        
      return TRUE;

  } else {

      return FALSE;
  }
  }

  /**
   * Searches strings for curse words
   *
   * @param string $value
   * @return void
   */
  private function curseWordsFilter(string $value)
  {
    $badWords = [
      "arse",
      "ass",
      "booty",
      "butt",
      "damn",
      "goddamn",
      "arsehole",
      "bitch",
      "bullshit",
      "pissed",
      "shit",
      "poop",
      "tits",
      "boobs",
      "bastard",
      "cock",
      "dick",
      "penis",
      "dickhead",
      "pussy",
      "vagina",
      "twat",
      "cunt",
      "fuck",
      "motherfucker",
    ];

    $matches = [];
    
    // If csv is passed to rule, commas are replaced with spaces to delineate beginning and endings of words
    $stringToCheck = str_replace(",", " ", $value);

    // If title is passed to rule, hyphens and underscores are replaced with spaces to delineate beginning and endings of words
    $stringToCheck = str_replace("-", " ", $stringToCheck);
    $stringToCheck = str_replace("_", " ", $stringToCheck);

    if (is_string($stringToCheck) && ! empty($stringToCheck)) {
        $regex = "/\b(" . implode('|', $badWords) . ")\b/i";

        $matchFound = preg_match_all($regex, $stringToCheck);

        if ($matchFound) {

            return false;
        
        } else {

            return true;
        }
    } else {

        return true;
    }
  }

  /**
   * File upload validation
   *
   * @param string $field - field name of file 
   * @return bool $valid - true if valid, false if not
   */
  private function validFile(string $field)
  {
    $valid = true;

    if (! isset($this->_file[$field]['error']) || is_array($this->_file[$field]['error']))
    {
      $this->addError("<b>{$field}</b> is not a valid file.");
      $valid = false;
    }

    switch ($this->_file[$field]['error']) 
    {
      case UPLOAD_ERR_OK:
        break;
      
      case UPLOAD_ERR_NO_FILE:
        $this->addError("<b>{$field}</b> is not sent.");
        $valid = false;
        break;

      case UPLOAD_ERR_INI_SIZE:
        $this->addError("<b>{$field}</b> exceeds the max filesize.");
        $valid = false;
        break;

      case UPLOAD_ERR_FORM_SIZE:
        $this->addError("<b>{$field}</b> exceeds the max filesize.");
        $valid = false;
        break;

      default:
        $this->addError("<b>{$field}</b> has an unknown error.");
        $valid = false;
    }

    if ($this->_file[$field]['size'] > 1000000)
    {
      $this->addError("<b>{$field}</b> exceeds the max filesize.");
      $valid = false;
    }

    $allowedMime = [
      'jpg'   => 'image/jpeg',
      'png'   => 'image/png',
      'gif'   => 'image/gif',
    ];

    if (array_search($this->_file[$field]['type'], $allowedMime))
    {
      $this->addError("<b>{$field} has an invalid file format.");
      $valid = false;
    }

    return $valid;
  }

  /**
   * Adds a new error message to array and appends a string of acceptable characters if error is on the pattern function.
   *
   * @param string $error
   * @param string $regexName
   * @return void
   */
  private function addError(string $error, string $regexName = NULL)
  {
    $allowedChars = '';

    if ($regexName != NULL)
    {
      switch ($regexName) 
      {
        case 'digit':
          $allowedChars = "Must contain only characters (0-9).";
          break;
        
        case 'alpha':
          $allowedChars = "Must contain only alphabetic characters (a-z or A-Z).";
          break;
        
        case 'alphanum':
          $allowedChars = "Must contain only alpha-numeric characters (a-z, A-Z, or 0-9).";
          break;

        case 'alphanumspace':
          $allowedChars = "Must contain only alpha-numeric and space characters(a-z, A-Z, 0-9, and ' '.";
          break;

        case 'alphanumchar':
          $allowedChars = "Must contain only alpha-numeric and special characters (a-z, A-Z, 0-9, or ! @ # $ % & * ? ; : . , - _).";

        case 'username':
          $allowedChars = "Must begin with an alphabetic character and may only contain alpha-numeric, hyphen, or underscore (a-z, A-Z, 0-9, or - _).";
          break;

        case 'password':
          $allowedChars = "Must contain at least one each of the following: uppercase letter, lowercase letter, numeric, and special character (a-z, A-Z, 0-9, and !@#$%&*?)";
          break;
      }
    }
    $this->_validationErrors[] = $error . $allowedChars;
  }

  /**
   * Returns array of error messages.
   *
   * @return void
   */
  public function errors()
  {
    return $this->_validationErrors;
  }

  /**
   * Returns true if all form fields are valid and false if even one is invalid. 
   *
   * @return boolean
   */
  public function isValid()
  {
    return $this->_isValid;
  }

}