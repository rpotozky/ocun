<?php
class OcunStatistics {

  private $ocunQuery;

  public function __construct(OcunQuery $ocunQuery){
    $this->ocunQuery = $ocunQuery;
  }

  public function morphemesInSentences() {
    $sentenceArray = $this->ocunQuery->sentence();
    $sentences = [];
    foreach($sentenceArray as $sentence){
      $sentences[] = [
        'form' => '<',
        'meaning' => '<'
      ];
      foreach($sentence as $morpheme){
        $sentences[] = $morpheme;
      }
      $sentences[] = [
        'form' => '>',
        'meaning' => '>'
      ];
    }
    return json_encode($sentences);
  }




}





 ?>
