<?php

interface OcunQueryInterface{

  // na construção será necessário inserir o código da gramática_fonte.
  // as funções deverão retornar um objeto JSON com os dados.
  public function functional();
  public function root();
  public function sentence();
  public function allomorph($meaning);
  public function homonym($form);
  public function morpheme($form, $meaning);
  public function word();
  public function morphemeInSentence($form, $meaning);

}








 ?>
