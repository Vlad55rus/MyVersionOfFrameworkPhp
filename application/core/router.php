<?php
class Router 
{
  private static $instance = null;
  private $routes = null;
  
  private function __construct() 
  {
    $this->routes = [];
  }
  
  public static function getInstance()
  {
    if(is_null(self::$instance))
    {
      self::$instance = new Router();
    }
    return self::$instance;
  }
  
  public function route(string $route, string $handler, string $method = "any")
  {
    //добавление массива роутов по методу
    if(empty($this->routes[$method]))
    {
      $this->routes[$method] = [];
    }
    //добавление роута
    $this->routes[$method][] = [
      'route'   => $route,
      'handler'  => $handler
    ];
  }
  
  public function process()
  {
    $request = preg_replace('~[?].*~', '', $_SERVER['REQUEST_URI']);
    $method = strtolower($_SERVER['REQUEST_METHOD']);
    //получаем список роутов
    $routes = array_merge(!empty($this->routes['any']) ? $this->routes['any'] : [], !empty($this->routes[$method]) ? $this->routes[$method] : []);
    //поиск обработчика по роуту
    foreach($routes as $route)
    {
      if(preg_match('~^/'.$route['route'].'$~Uui', $request))
      {
        $handler_parts = explode('/', $route['handler']);
        //проверка обработчика
        if(count($handler_parts) < 1)
        {
          echo 'Handler is empty!';
          require_once APP_PATH.'404.php';
          die();
        }
        //проверка наличия файла контроллера
        if(!file_exists(APP_PATH.'application/controllers/'.$handler_parts[0].'_controller.php'))
        {
          echo 'Controller file not exists!';
          require_once APP_PATH.'404.php';
          die();
        }
        //подключение файла контроллера
        require_once(APP_PATH.'application/controllers/'.$handler_parts[0].'_controller.php');
        //проверка наличия класса обработчика  
        $class_name = ucfirst($handler_parts[0]).'Controller';
        if(!class_exists($class_name))
        {
          echo 'Controller class not exists!';
          require_once APP_PATH.'404.php';
          die();
        }
        $obj = new $class_name();
        $method = 'index';
        //проверка метода
        if(count($handler_parts) > 1)
        {
          if(!method_exists($obj, $handler_parts[1]))
          {
            echo 'Required method not exists!';
            require_once APP_PATH.'404.php';
            die();
          }
          $method = $handler_parts[1];
        }
        else
        {
          $obj->$method();
          return;
        }
        //аргументы
        $args = [];
        $route_parts = explode('/', $route['route']);
        $request_parts = explode('/', preg_replace('~^\/~', '', $request));
        foreach($route_parts as $index => $part)
        {
          if(preg_match('~\[.*\]\+*~', $part, $match))
          {
            $args[] = $request_parts[$index];
          }
        }
        if(empty($args))
        {
          $obj->$method();
        }
        else
        {
          call_user_func_array([$class_name, $method], $args);
        }
        return;
      }
    }
    echo 'Page not exists!';
    require_once APP_PATH.'404.php';
  }
}