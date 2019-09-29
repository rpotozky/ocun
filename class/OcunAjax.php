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

}






 ?>
