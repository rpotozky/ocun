<?php

interface OcunUserDataBaseInterface {

  public function authenticate($email, $passwordString);
  public function create($email, $passwordString, $name);
  public function changePassword($email, $passwordString);
  public function fetch($sql);

}




 ?>
