<?php

interface OcunQueryInterface{

  // na construção será necessário inserir o código da gramática_fonte.
  // as funções deverão retornar um objeto JSON com os dados.
  public function functional();
  public function root();
  public function allomorph($functionalMeaning);
  public function wordPattern($meaning);
  public function realization($phonologicalForm);
  public function sentence();

}








 ?>
