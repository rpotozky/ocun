<?php

interface OcunDataBaseInterface {

  public function insert($sql, $listOfFields);
  public function query($sql);

}


?>
