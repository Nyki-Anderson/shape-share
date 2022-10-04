<?php

namespace app\Models;

use framework\Database\Orm\Orm;
use framework\Exception\RuntimeException;
use framework\Security\PasswordManagerInterface;
use DateTime;

class MemberModel extends Orm
{
  private $table = 'members';
  
  /** @var PasswordManagerInterface */
  private $passwordManager;

  protected $fields = [
    'username'        => null,
    'password'        => null,
    'role'            => null,
    'profile_image'   => null,
    'profile_views'   => null,
    'last_login'      => null,
    'about_me'        => null,
    'bday_month'      => null,
    'bday_day'        => null,
    'bday_year'       => null,
    'gender'          => null,
    'occupation'      => null,
    'hometown'        => null,
    'country'         => null,
    'fav_shape'       => null,
    'fav_color'       => null,
    'modified'        => null,
  ];

  /**
   * MemberModel constructor
   * @param PasswordManagerInterface $passworManager
   * @throws \Exception
   *
   * @param PasswordManagerInterface $passwordManager
   */
  public function __construct(PasswordManagerInterface $passwordManager)
  {
    parent::__construct($this->table);
    $this->passwordManager = $passwordManager;
  }

  public function lastLogin(string $username)
  {
    $datetime = new DateTime;
    $lastLogin = $datetime->format('Y-m-d H:i:s');

    $stmt = $this->db->prepare("UPDATE " . $this->table . " SET last_login = ? WHERE username = ?");
    $stmt->execute([$lastLogin, $username]);

    $updated = $stmt->rowCount();

    if ($updated === 1){
      return true;
    
    } else {
      return false;
    }
  }

  private function updateModified(array $data)
  {
      $datetime = new DateTime();
      $modified = $datetime->format('Y-m-d H:i:s');

      if ($data['modified'] === null) {

          $data['modified'] = $modified;
      
          return $data;
      }

      return $data;
  }

  private function usernameExists(string $username)
  {
    $stmt = $this->db->prepare("SELECT username FROM " . $this->table . " WHERE username = ?");

    $stmt->execute([$username]);

    $exists = $stmt->rowCount();

    if ($exists != 0) {
      return true;
    
    } else {
      return false;
    }
  }

  private function hashPassword(array $data)
  {
    if (isset($data['password'])) {

      $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

      return $data;
  }
  }

  public function addMember(array $member)
  {
    $member = $this->updateModified($member);
    $member = $this->hashPassword($member);

    if ($this->usernameExists($member['username']) || ! $this->lastLogin($member['username'])){

      return false;

    } else {

      $stmt = $this->db->prepare("INSERT " . $this->table . " SET username = ?, password = ?, role = ?, profile_image = ?, profile_views = ?, about_me = ?, bday_month = ?, bday_day = ?, bday_year = ?, gender = ?, occupation = ?, hometown = ?, country = ?, fav_color = ?, fav_shape = ?, modified = ? ");

      $stmt->execute([$member['username'], $member['password'], $member['role'], $member['profile_image'], $member['profile_views'], $member['about_me'], $member['bday_month'], $member['bday_day'], $member['bday_year'], $member['gender'], $member['occupation'], $member['hometown'], $member['country'], $member['fav_color'], $member['fav_shape'], $member['modified']]);

      if ($stmt->rowCount() === 1) {
        return true;
      
      } else {
        
        return false;
      }
    }
  }

  public function getMembers()
  {
    $this->db->prepare("SELECT * FROM " . $this->table)->execute(); 
  }

}