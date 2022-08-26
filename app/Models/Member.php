<?php

namespace Models;

use framework\Database\DatabasePDO;

class Member
{
  private $db;

  private $table = 'members';

  protected $fields = [
    'id'              => null,
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
    'created'         => null,
    'modified'        => null,
  ];
  
  public function __construct()
  {
    $this->db = new DatabasePDO;
  }

  public function getMembers()
  {
    $this->db->query("SELECT * FROM " . $this->table);

    return $this->db->resultSet(); 
  }

}