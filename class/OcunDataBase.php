<?php

class OcunDataBase implements OcunDataBaseInterface {

  private $ocunException;
  private $pdo;

  // Constrói injetando um objeto OcunException, para lidar com os erros;
  public function __construct(OcunException $ocunException) {
    $this->ocunException = $ocunException;
    $this->connect();

  }

  private function connect(){
    // Conecta no banco de dados. No servidor ocun.latip.com.br, mudar os dados de conexão e usar utf8-mb4.
    try{
      //Variáveis de conexão estão no arquivo langserv.conf
      include __DIR__ . '/../servinfo/langserv.conf';
      $this->pdo = new PDO($servData, $usrName, $usrPassword);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e){
      $this->ocunException->display($e);
    }

  }

  // retorna V ou F, de acordo com o sucesso da operação
  public function insert($sql, $listOfFields) {
    preg_match_all('/:[a-zA-z]+/', $sql, $matches);
    if (sizeof($matches[0]) != sizeof($listOfFields)){
      return false;
    }
    try {
      $stmt = $this->pdo->prepare($sql);
      for($i = 0; $i < sizeof($listOfFields); $i++){
        $stmt->bindValue($matches[0][$i], $listOfFields[$i]);
      }
      $stmt->execute();
      return true;
    }
    catch (PDOException $e){
      $this->ocunException->display($e);
    }
  }

  // retorna o objeto pdo com a consulta realizada
  public function query($sql) {
    try {
      return $this->pdo->query($sql);
    }
    catch (PDOException $e){
      $this->ocunException->display($e);
    }
  }

}

?>
