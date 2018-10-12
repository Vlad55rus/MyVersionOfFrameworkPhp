<?php
class IO
{
  //доступ к данным переданным методом GET
  public static function get($key)
  {
    if(array_key_exists($key, $_GET))
    {
      return $_GET[$key];
    }
    return null;
  }
  
  //доступ к массиву данных переданных методом POST
  public static function post($key)
  {
    if(array_key_exists($key, $_POST))
    {
      return $_POST[$key];
    }
    return null;
  }
  
  //доступ к необрботанным данным, переданным методом POST
  public static function postRaw()
  {
    return self::readFile('php://input');
  }
  
  //форматированный вывод отладочной информации
  public static function varDump($string)
  {
    echo '<pre>';
    var_dump($string);
    echo '</pre>';
  }
  
  //прочитать содержимое файла в строку
  public static function readFile(string $file_path)
  {
    $file_content = null;
    $file = fopen($file_path, 'r');
    if($file) {
      $file_content = '';
      while(!feof($file))
      {
        $file_content .= fread($file, 255);
      }
    }
    return $file_content;
  }
  
  
}