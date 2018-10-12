<?php
class UserModel extends Model
{
  
  public function getUserById($id)
  {
    $ret = null;
    $res = $this->db->query('SELECT 
      id,
      firstname,
      lastname,
      birthdate,
      login,
      email,
      create_date,
      update_date,
      active
    FROM users WHERE id = ?',[$id]);
    if(!empty($res))
    {
      $ret = $res[0];
    }
    return $ret;
  }
  
  public function getUsers() : array
  {
    return $this->db->query('SELECT 
      id,
      firstname,
      lastname,
      birthdate,
      login,
      email,
      create_date,
      update_date,
      active 
    FROM users');
  }
 
  public function addUser(string $login, string $email, string $password) : int
  {
    $res = $this->db->query('INSERT INTO users (login, email, password, create_date, update_date) VALUES (?, ?, ?, ?, ?)', [
      $login,
      $email,
      md5($password),
      (new DateTime())->format(DateTime::W3C),
      (new DateTime())->format(DateTime::W3C)
    ]);
    if($res)
    {
      return $this->db->getlastId();
    }
    return -1;
  }
  
  public function login(string $login, string $password) : int
  {
    $res = $this->db->query('SELECT id FROM users WHERE (login = ? OR email = ?) AND password = ?', [$login, $login, md5($password)]);
    if(!empty($res))
    {
      return $res[0]['id'];
    }
    return -1;
  }
  
  public function updateUser(int $id, string $firstname, string $lastname, string $login, string $email, DateTime $birthdate)
  {
    return $this->db->query('
    UPDATE users SET
      firstname = ?,
      lastname = ?,
      birthdate = ?,
      login = ?,
      email = ?,
      update_date = NOW()
    WHERE id = ?  
      ', [
        $firstname,
        $lastname,
        $birthdate->format(DateTime::W3C),
        $login,
        $email,
        $id
      ]);
  }
  
  public function changePassword(int $id, string $password)
  {
    return $this->db->query('
    UPDATE users SET
      password = ?,
      update_date = NOW()
    WHERE id = ?  
      ', [
        md5($password),
        $id
      ]);
  }
}