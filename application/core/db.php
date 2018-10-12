<?php
class DB
{
  private static $instance = null;
  
  public static function getInstance()
  {
    if(is_null(self::$instance))
    {
      self::$instance = new DB();
    }
    return self::$instance;
  }
  
  private $connection = null;
  
  private $config = null;
  
  private $error = null;
  
  public function __construct()
  {
    $this->config = Configuration::getConfiguration(); 
    $this->connection = new PDO(
      'mysql:dbname='.$this->config->database['dbname'].';host='.$this->config->database['host'].';', 
      $this->config->database['login'], 
      $this->config->database['password']);
  }
  
  public function query(string $request, array $params = [])
  {
    $lastId = $this->getLastId();
    $statment = $this->connection->prepare($request);
    $result = $statment->execute($params);
    $this->error = $statment->errorInfo();
    if($result)
    {
      if($this->getLastId() != $lastId)
      {
        return $result;
      }
      return $statment->fetchAll(PDO::FETCH_ASSOC);
    }
    return $result;
  }
  
  public function getError()
  {
    return $this->error;
  }
  
  public function getLastId()
  {
    return $this->connection->lastInsertId();
  }
}