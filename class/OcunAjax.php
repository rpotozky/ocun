<?php

class OcunAjax {

  private $ocunException;

  public function __construct(OcunException $ocunException) {
    $this->ocunException = $ocunException;
  }

  public function checkEmailDoesNotExist() {
    if (isset($_GET['email'])) {
      $db = new OcunUserDataBase($this->ocunException);
      $qu = "SELECT `email` FROM `user` WHERE `email` = '" . $_GET['email'] . "'";
      if(!$db->fetch($qu)) {
        return "true";
      }
    }
    return "false";
  }

  public function getFunctional() {
    if (isset($_GET['id'])) {
      $db = new OcunDataBase($this->ocunException);
      $qu = new OcunQuery($db, $_GET['id']);
      return json_encode($qu->functional());
    }
  }

  public function getRoot() {
    if (isset($_GET['id'])) {
      $db = new OcunDataBase($this->ocunException);
      $qu = new OcunQuery($db, $_GET['id']);
      return json_encode($qu->root());
    }
  }

  public function getSentence() {
    if (isset($_GET['id'])) {
      $db = new OcunDataBase($this->ocunException);
      $qu = new OcunQuery($db, $_GET['id']);
      return json_encode($qu->sentence());
    }
  }

  public function getAllFixedData() {
    if (isset($_GET['id'])) {
      $db = new OcunDataBase($this->ocunException);
      $qu = new OcunQuery($db, $_GET['id']);
      return json_encode([$qu->functional(), $qu->root(), $qu->sentence()]);
    }
  }

  public function getAllomorph() {
    if (isset($_GET['meaning']) && isset($_GET['id'])) {
      $db = new OcunDataBase($this->ocunException);
      $qu = new OcunQuery($db, $_GET['id']);
      return json_encode($qu->allomorph($_GET['meaning']));
    }
  }


}






 ?>
