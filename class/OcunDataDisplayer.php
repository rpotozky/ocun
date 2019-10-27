<?php

class OcunDataDisplayer {

  private $ocunQuery;

  public function __construct(OcunQuery $ocunQuery){
    $this->ocunQuery = $ocunQuery;
  }

  private function buildSentence($sentenceBase){
    $sentenceBlocks = [];
    foreach($sentenceBase as $sentence){
      $sentenceTextOriginal = '';
      $sentenceTextGloss = '';
      $sentenceMorphemes = [];
      foreach($sentence as $key => $morpheme){
        if($key != 0 && $sentence[$key]['word_id'] != $sentence[$key-1]['word_id']){
          $sentenceTextOriginal .= ' ';
          $sentenceTextGloss .= ' ';
          $sentenceMorphemes[] = ['form' => '&nbsp;'];
        } elseif($key != 0) {
          $sentenceTextOriginal .= '-';
          $sentenceTextGloss .= '-';
        }
        $sentenceTextOriginal .= $morpheme['form'];
        $sentenceTextGloss .= $morpheme['meaning'];
        $sentenceMorphemes[] = ['form' =>  $morpheme['form'], 'meaning' => $morpheme['meaning']];
      }
      $sentenceText = [
        'original' => $sentenceTextOriginal,
        'gloss' => $sentenceTextGloss,
        'translation' => $sentence[0]['translation']
      ];
      $sentenceBlocks[] = [
        'morphemes' => $sentenceMorphemes,
        'translation' => $sentence[0]['translation'],
        'text' => $sentenceText
      ];
    }
    return $sentenceBlocks;
  }

  private function morphemeStats($form, $meaning){
    $sentenceArray = $this->ocunQuery->sentence();
    $sentencesWithMorpheme = [];
    $morphemesInSource = 0;
    $morphemeMatch = 0;
    $meaningMatch = 0;
    $formMatch = 0;
    foreach($sentenceArray as $sentence){
      $morphemesInSource += count($sentence);
      foreach($sentence as $morpheme){
        if($morpheme['form'] == $form && $morpheme['meaning'] == $meaning){
          $sentencesWithMorpheme[] = $sentence;
          $morphemeMatch++;
        }
        if($morpheme['form'] == $form){
          $formMatch++;
        }
        if($morpheme['meaning'] == $meaning){
          $meaningMatch++;
        }
      }
    }
    $morphemeProbability = $morphemeMatch / $morphemesInSource;
    $morphemeLogP = log($morphemeProbability, 2) * -1;
    $formProbability = $formMatch / $morphemesInSource;
    $formLogP = log($formProbability, 2) * -1;
    $meaningProbability = $meaningMatch / $morphemesInSource;
    $meaningLogP = log($meaningProbability, 2) * -1;
    return [
      'sentences' => $sentencesWithMorpheme,
      'sentence_count' => count($sentencesWithMorpheme),
      'morpheme_count' => $morphemesInSource,
      'morpheme_probability' => $morphemeProbability,
      'morpheme_logP' => $morphemeLogP,
      'form_probability' => $formProbability,
      'form_logP' => $formLogP,
      'meaning_probability' => $meaningProbability,
      'meaning_logP' => $meaningLogP,
      'morpheme_match'=> $morphemeMatch,
      'form_match' => $formMatch,
      'meaning_match' => $meaningMatch
    ];
  }

  public function sentence() {
    $sentenceArray = $this->ocunQuery->sentence();
    $language = $this->ocunQuery->language();
    $sentenceBlocks = $this->buildSentence($sentenceArray);
    return [
      'template' => 'workspace_sentence_list.php',
      'variables' => [
        'language' => $language['name'],
        'sentence_count' => count($sentenceArray),
        'sentences' => $sentenceBlocks,
        'source_id' => $this->ocunQuery->sourceID
      ]
    ];
  }

  public function morpheme() {
    if (!isset($_GET['form']) && !isset($_GET['meaning'])){
      return [
        'template' => 'workspace_error.php',
        'variables' => []
      ];
    }
    $language = $this->ocunQuery->language();
    $form = $_GET['form'];
    $meaning = $_GET['meaning'];
    $allomorphs = $this->ocunQuery->allomorph($meaning);
    $homonyms = $this->ocunQuery->homonym($form);
    $stats = $this->morphemeStats($form, $meaning);
    $sentenceBlocks = $this->buildSentence($stats['sentences']);
    return [
        'template' => 'workspace_morpheme.php',
        'variables' => [
          'language' => $language['name'],
          'form' => $form,
          'meaning' => $meaning,
          'stats' => $stats,
          'sentences' => $sentenceBlocks,
          'allomorphs' => $allomorphs,
          'homonyms' => $homonyms,
          'source_id' => $this->ocunQuery->sourceID,
          'sentence_count' => count($this->ocunQuery->sentence())
        ]
      ];
    // equivalent to morpheme statistics.
  }


}





 ?>
