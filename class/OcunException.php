<?php

class OcunException implements OcunExceptionInterface {

  public function display($e) {
    // mostrar o erro em uma página...
    echo $e;
  }

}



?>
