<?php
abstract class Controller
{
  protected $loader = null;
  protected $config = null;
  
  public function __construct()
  {
    $this->loader = new Loader();
    $config = Configuration::getConfiguration();
  }
  
  public abstract function index();
  
  //парсинг необработанных данных в объект класса stdClass 
  protected function apiData($data_flag = true, $token_flag = false)
  {
    $data = null;
    //парсинг JSON
    try
    {
      $data = json_decode(IO::postRaw());
    }
    catch(Exception $e)
    {
      echo json_encode([
        'success' => 0,
        'error' => [
          'code' => 101,
          'message' => 'Wrong JSON'
        ]
      ]);
      die();
    }
    //проверка наличия токена
    if($token_flag)
    {
      if(!property_exists($data, 'token'))
      {
        echo json_encode([
        'success' => 0,
        'error' => [
          'code' => 102,
          'message' => 'Token section not exists'
        ]
      ]);
      die();
      }
    }
    //проверка наличия данных
    if($data_flag)
    {
      if(!property_exists($data, 'data'))
      {
        echo json_encode([
          'success' => 0,
          'error' => [
            'code' => 103,
            'message' => 'Data section not exists'
          ]
        ]);
        die();
      }
    }
    return $data;
  }
  
  
}