<?php
  //загрузка роутера
  $router = Router::getInstance();
  //список роутов
  $router->route('', 'base');
  $router->route('api/login', 'login/api_login', 'post');
  $router->route('api/logout', 'login/api_logout', 'post');

