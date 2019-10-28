<?php

interface OcunQueryInterface{

  public function language();
  public function root();
  public function sentence();
  public function functional();
  public function allomorph($meaning);
  public function functionalMeaning($meaning);
  public function homonym($form);
  public function morpheme($form, $meaning);
  public function morphemes();
  public function word();
  public function morphemeInSentence($form, $meaning);


}








 ?>
