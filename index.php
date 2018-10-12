<?php
define('APP_PATH', 'H:\PhpProjects\MyFramework\Alexandr1994-webomstu-ec346df2f2f8/');
//подключение файлов ядра
$core = glob(APP_PATH.'application/core/*.php');
foreach($core as $path)
{
  require_once($path);
}
//загрузка списка роутов
require_once(APP_PATH.'application/routes.php');
//загрузка роутера
$router = Router::getInstance();
$router->process();
