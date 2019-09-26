<?php

class OcunLinguisticDataFeeder implements OcunLinguisticDataFeederInterface {

  private $OcunDataBase;

  public function __construct(OcunDataBase $OcunDataBase) {
    $this->OcunDataBase = $OcunDataBase;
  }

  //Makes a string in the format "m-m-m m-m m-m-m m-m-m" into and array [[m,m,m],[m,m],[m,m,m],[m,m,m]],
  //where m stands for morpheme and a set of morphemes is a word.
  private function ParseData($string) {
    $arrayWithWords = array_diff(explode(" ", $string), [""]);
    $parsedData = array();
    foreach ($arrayWithWords as $word) {
      $parsedData[] = explode("-", $word);
    }
    return $parsedData;
  }

  //Stores the morphemes into the morpheme pools and grab the morpheme IDs. Returns the same array structure but with
  //morpheme IDs instead of morpheme form and meanings.
  private function StoreMorphemeDataReturnArrayOfMorphemeIDs($sourceID, $parsedLanguageData, $parsedMeaningData) {
    $arrayOfMorphemeIDs = array();
    for($i=0;$i<sizeof($parsedLanguageData);$i++){
      $arrayOfMorphemeIDsInWord = array();
      for($j=0;$j<sizeof($parsedLanguageData[$i]);$j++){
        $uniqueString = $parsedLanguageData[$i][$j] . '->' . $parsedMeaningData[$i][$j];
        $sql = 'INSERT IGNORE INTO `pool` SET `source_id` = :sourceID, `form` = :form, `meaning` = :meaning,
        `unique_str` = :uniqueString';
        $this->OcunDataBase->insert($sql,[$sourceID, $parsedLanguageData[$i][$j], $parsedMeaningData[$i][$j], $uniqueString]);
        $sql = 'SELECT `id` FROM `pool` WHERE `unique_str` = \'' . $uniqueString . '\'';
        $arrayOfMorphemeIDsInWord[] = $this->OcunDataBase->query($sql)->fetch(PDO::FETCH_ASSOC)['id'];
      }
      $arrayOfMorphemeIDs[] = $arrayOfMorphemeIDsInWord;
    }
    return $arrayOfMorphemeIDs;
  }

  //Inputs the array with morpheme IDs, stores the words and returns an array of word IDs.
  private function StoreWordDataReturnArrayOfWordIDs($sourceID, $arrayOfMorphemeIDs){
    $arrayOfWordIDs = array();
    foreach($arrayOfMorphemeIDs as $word){
      $uniqueString = implode(",", $word);
      $sql = 'INSERT IGNORE INTO `word` SET `source_id` = :sourceID, `unique_str` = :uniqueString, `length` = :length';
      $this->OcunDataBase->insert($sql, [$sourceID, $uniqueString, sizeof($word)]);
      $sql = 'SELECT `id` FROM `word` WHERE `unique_str` = \'' . $uniqueString . '\'';
      $wordID = $this->OcunDataBase->query($sql)->fetch(PDO::FETCH_ASSOC)['id'];
      $arrayOfWordIDs[] = $wordID;
      for($i=0; $i<sizeof($word); $i++){
        $sql = 'INSERT IGNORE INTO `m_chain` SET `word_id` = :wordID, `ord` = :ord,  `morpheme_id` = :morphemeID';
        $this->OcunDataBase->insert($sql,[$wordID, $i, $word[$i]]);
      }
    }
    return $arrayOfWordIDs;
  }

  //Stores Phrases and don't return anything.
  private function StorePhraseData($sourceID, $arrayOfWordIDs, $translation){
    $uniqueString = implode(",", $arrayOfWordIDs) . ',' . $translation;
    $sql = 'INSERT IGNORE INTO `phrase` SET `source_id` = :sourceID, `unique_str` = :uniqueString, `length` = :length,
    `translation` = :translation';
    $this->OcunDataBase->insert($sql, [$sourceID, $uniqueString, sizeof($arrayOfWordIDs), $translation]);
    $sql = 'SELECT `id` FROM `phrase` WHERE `unique_str` = \'' . $uniqueString . '\'';
    $phraseID = $this->OcunDataBase->query($sql)->fetch(PDO::FETCH_ASSOC)['id'];
    for($i=0; $i<sizeof($arrayOfWordIDs); $i++){
      $sql = 'INSERT IGNORE INTO `w_chain` SET `phrase_id` = :phraseID, `ord` = :ord, `word_id` = :wordID';
      $this->OcunDataBase->insert($sql, [$phraseID, $i, $arrayOfWordIDs[$i]]);
    }
  }

  public function feed($sourceID, $languageData, $meaningData, $translation){
    $this->StorePhraseData($sourceID,
    $this->StoreWordDataReturnArrayOfWordIDs($sourceID,
    $this->StoreMorphemeDataReturnArrayOfMorphemeIDs($sourceID,
    $this->ParseData($languageData),
    $this->ParseData($meaningData))),
    $translation);
  }
}



 ?>
