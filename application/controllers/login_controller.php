<?php
class LoginController extends Controller
{
  public function index()
  {
    
  }
  
  public function login()
  {
    
  }
  
  public function logout()
  {
    
  }
  
  public function api_login()
  {
    $data = $this->apiData()->data;
        //проверка postData
    if(empty($data) || !property_exists($data, 'login') || !property_exists($data, 'password'))
    {
      echo json_encode([
        'success' => 0,
        'error' => [
          'code' => 104,
          'message' => 'Wrong data set'
        ]
      ]);
      die();
    }
    //модель сессии
    $session_model = $this->loader->getModel('session');
    //getting token
    $token = $session_model->login($data->login, $data->password);
    if(empty($token))
    {
      echo json_encode([
        'success' => 0,
        'error' => [
          'code' => 201,
          'message' => 'Wrong login or password'
        ]
      ]);
      die();
    }
    echo json_encode([
        'success' => 1,
        'data' => [
          'token' => $token
        ]
      ]);
      die();
  }
  
  public function api_logout()
  {
    $token = $this->apiData(false, true)->token;
    //модель сессии
    $session_model = $this->loader->getModel('session');
    $user_id = $session_model->authentication($token);
    if($user_id > 0)
    {
      $session_model->logout($token);
      echo json_encode([
        'success' => 1
      ]);
      die();
    }
    echo json_encode([
        'success' => 0,
        'error' => [
          'code' => 105,
          'message' => 'Wrong token'
        ]
    ]);  
    die();
  }
}