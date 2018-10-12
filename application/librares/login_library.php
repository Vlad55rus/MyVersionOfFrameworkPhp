<?php 
class LoginLibrary
{
  //сохраненные в сессии данные пользоватля
  private $user = [];
  
  //авторизация
  public function login($id, $login, $email) : bool
  {
    if(session_start())
    {
      $this->user = [];
      $_SESSION['user_id'] = $this->user['id'] = $id;
      $_SESSION['user_login'] = $this->user['login'] = $login;
      $_SESSION['user_email'] = $this->user['email'] = $email;
      session_commit();
      return true;
    }
    return false;
  }
  
  //выход и удаление из сессии данных пользователя
  public logout() : bool
  {
    if(session_start())
    {
      $this->user = [];
      unset($_SESSION['user_id']);
      unset($_SESSION['user_login']);
      unset($_SESSION['user_email']);
      session_commit();
      return true;
    }
    return false;
  }
  
  //получить сохраненные в сессии данные пользователя
  public function getUser() : array
  {
    if(empty($this->user))
    {
      //если массив данных о пользователе пуст читаем их из сессии
      if(session_start())
      {
        //user id
        if(array_key_exist('user_id', $_SESSION) &&
           array_key_exist('user_login', $_SESSION) &&
           array_key_exist('user_email', $_SESSION))
        {
          $this->user['id'] = $_SESSION['user_id'];
          $this->user['login'] = $_SESSION['user_login'];
          $this->user['email'] = $_SESSION['user_email'];
          session_commit();
        }
        else
        {
          session_commit();
          $this->logout();
        }
      }
    }
    return $this->user;
  }
  
  //проверка авторизации пользователя
  public checkLogin() : bool
  {
    return !empty($this->getUser());
  }
}