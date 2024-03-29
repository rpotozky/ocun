<?php

class OcunUserDataBase implements OcunUserDataBaseInterface {

  private $ocunException;

  // Constrói injetando um objeto OcunException, para lidar com os erros;
  public function __construct(OcunException $ocunException) {
    $this->ocunException = $ocunException;
  }

  private function connect() {
    //variáveis de conexão estão no arquivo usrserv.conf
    include __DIR__ . '/../servinfo/usrserv.conf';
    $pdo = new PDO($servData, $usrName, $usrPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  }

  //retorna verdadeiro se autenticado, falso se não autenticado.
  public function authenticate($email, $passwordString) {
    try {
      $sql = 'SELECT `password` FROM `user` WHERE `email` = \'' . $email . '\'';
      $result = $this->connect()->query($sql)->fetch(PDO::FETCH_ASSOC);
      if(password_verify($passwordString, $result['password'])){
        return true;
      }
      return false;
    }
    catch(PDOException $e){
      $this->ocunException->display($e);
    }
  }

  //pede email, senha e nome completo e cria novo usuário
  public function create($email, $passwordString, $name) {
    try {
      $sql = 'INSERT INTO `user` SET `email` = :gemail, `password` = :gpassword, `name` = :gname';
      $passwordHash = password_hash($passwordString, PASSWORD_BCRYPT);
      $stmt = $this->connect()->prepare($sql);
      $stmt->bindValue(':gemail', $email);
      $stmt->bindValue(':gpassword', $passwordHash);
      $stmt->bindValue(':gname', $name);
      $stmt->execute();
    }
    catch(PDOException $e){
      $this->ocunException->display($e);
    }

  }

  public function saveNotes($email, $notes){
    try {
      $sql = 'UPDATE `user` SET `notes` = :gnotes WHERE `email` = "' . $email . '"';
      $stmt = $this->connect()->prepare($sql);
      $stmt->bindValue(':gnotes', $notes);
      $stmt->execute();
    }
    catch(PDOException $e){
      $this->ocunException->display($e);
    }

  }

  //muda a senha para um email
  public function changePassword($email, $passwordString){
    $passwordHash = password_hash($passwordString, PASSWORD_BCRYPT);
    $sql = 'UPDATE `user` SET `password` = \'' . $passwordHash . '\' WHERE `email` = \'' . $email . '\'';
    try{
      $this->connect()->exec($sql);
    }
    catch(PDOException $e){
      $this->ocunException->display($e);
    }

  }

  //buscar dado específico
  public function fetch($sql){
    return $this->connect()->query($sql)->fetch(PDO::FETCH_ASSOC);
  }

}




 ?>
