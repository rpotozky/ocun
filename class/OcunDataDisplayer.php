<?php

class OcunDataDisplayer {

  private $ocunQuery;

  public function __construct(OcunQuery $ocunQuery){
    $this->ocunQuery = $ocunQuery;
  }

  public function sentence() {
    $sentenceArray = $this->ocunQuery->sentence();
    $sentenceBlocks = [];
    foreach($sentenceArray as $sentence){
      $sentenceTextOriginal = '';
      $sentenceTextGloss = '';
      $sentenceHTML = '';
      foreach($sentence as $key => $morpheme){
        if($key != 0 && $sentence[$key]['word_id'] != $sentence[$key-1]['word_id']){
          $sentenceTextOriginal .= ' ';
          $sentenceTextGloss .= ' ';
          $sentenceHTML .= '<span style="margin-right: 10px;">&nbsp;</span>';
        } elseif($key != 0) {
          $sentenceTextOriginal .= '-';
          $sentenceTextGloss .= '-';
        }
        $sentenceTextOriginal .= $morpheme['form'];
        $sentenceTextGloss .= $morpheme['meaning'];
        $onClick = 'Ajax(\'ajax.php?action=displayData&id='.$this->ocunQuery->sourceID.'&function=morpheme&form='.$morpheme['form'].'&meaning='.$morpheme['meaning'].'\', displayAjaxDataInWorkspace)';
        $sentenceHTML .= '<button class="button-sentence" onclick="'.$onClick.'"><p>' . $morpheme['form'] . '</p><p>' . $morpheme['meaning'] . '</p></button>';
      }
      $sentenceHTML .= '<br><button class="button-sentence-translation"><p>"'.$sentence[0]['translation'].'"</p></button>';
      $sentenceText = '<br>'.$sentenceTextOriginal.'<br>'.$sentenceTextGloss.'<br>'.$sentence[0]['translation'].'<br>';
      $sentenceBlocks[] = ['HTML' => $sentenceHTML, 'text' => $sentenceText];
    }
    return [
      'template' => 'workspace_sentence_list.php',
      'variables' => [
        'sentence_count' => count($sentenceArray),
        'sentences' => $sentenceBlocks
      ]
    ];
  }

  public function morpheme() {
    // equivalent to morpheme statistics.
  }


}





 ?>
