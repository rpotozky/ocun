<?php
class OcunQuery implements OcunQueryInterface {

  private $ocunDataBase;
  private $sourceID;

  //Constrói injetando um objeto do Banco de Datos e o código da gramática fonte
  public function __construct(OcunDataBase $ocunDataBase, $sourceID) {
    $this->ocunDataBase = $ocunDataBase;
    $this->sourceID = $sourceID;
  }

  //Pega lista de significados funcionais e abreviações da gramática fonte.
  //Retorna objeto JSON com os dados.
  public function functional(){
    $sql = "SELECT * FROM `f_meaning` WHERE `source_id`=" . $this->sourceID;
    return $this->ocunDataBase->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  //Retorna as raízes pedindo os morfemas cujo significado total não tem correspondência em f_meaning
  //e a parte inicial do significado, até o primeiro ponto (.), também não.
  //Casos como raiz.FUNCIONAL apareceriam na consulta. A consulta excluiria casos como FUNCIONAL.raiz,
  //que, a princípio, não parecem existir nas gramáticas.
  public function root(){
    $sql = "SELECT * FROM `pool` WHERE `source_id`=". $this->sourceID . " AND SUBSTRING_INDEX(`meaning`, '.',1)
    NOT IN (SELECT `abbreviation` FROM `f_meaning` WHERE `source_id`=" . $this->sourceID . ") AND `meaning`
    NOT IN (SELECT `abbreviation` FROM `f_meaning` WHERE `source_id`=" . $this->sourceID . ")";
    return $this->ocunDataBase->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  //Retorna a lista de sentenças da gramática, com os códigos das palavras e morfemas.
  public function sentence(){
    $sql = "SELECT DISTINCT `w_chain`.`phrase_id` AS `id`, `phrase`.`translation` AS `translation`,
    `w_chain`.`word_id` AS `word_id`, `m_chain`.`morpheme_id` AS `morpheme_id`, `pool`.`form` AS `form`,
    `pool`.`meaning` AS `meaning`, `phrase`.`source_id` AS `source_id` FROM `phrase`, `w_chain`, `m_chain`, `pool`
    WHERE `phrase`.`source_id` = '" . $this->sourceID . "'
    AND `w_chain`.`phrase_id` = `phrase`.`id`
    AND `w_chain`.`word_id` = `m_chain` . `word_id`
    AND `m_chain`.`morpheme_id` = `pool`.`id`
    ORDER BY `phrase`.`id` ASC";
    return array_values($this->ocunDataBase->query($sql)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC));
  }

  //Retorna a tabela com morfemas que contenham um significado em específico.
  public function allomorph($meaning){
    $sql = "SELECT * FROM `pool` WHERE `source_id`=" . $this->sourceID . " AND (`meaning`='" . $meaning . "'
      OR `meaning` LIKE '%." . $meaning . "'
      OR `meaning` LIKE '" . $meaning .  ".%'
      OR `meaning` LIKE '%." . $meaning . ".%')";
    return $this->ocunDataBase->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  //Retorna a tabela com morfemas que contenham uma forma em epecífico.
  public function homonym($form){
    $sql = "SELECT * FROM `pool` WHERE `source_id`=" . $this->sourceID . " AND `form`='" . $functionalMeaning . "'";
    return $this->ocunDataBase->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  //Retorna um morfema único (previne duplicatas)
  public function morpheme($form, $meaning){
    $sql = "SELECT * FROM `pool` WHERE `source_id`=" . $this->sourceID . " AND (`meaning`='" . $meaning . "'
      OR `meaning` LIKE '%." . $meaning . "'
      OR `meaning` LIKE '" . $meaning .  ".%'
      OR `meaning` LIKE '%." . $meaning . ".%')
      AND `form`='" . $functionalMeaning . "'";
    return $this->ocunDataBase->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  //palavra por id
  public function word($wordId){
    $sql = "SELECT DISTINCT `m_chain`.`word_id` AS `word`,
    `m_chain`.`ord` AS `order`,
    `m_chain`.`morpheme_id` AS `morpheme_id`,
    `pool`.`meaning` AS `meaning`,
    `pool`.`form` AS `form`
    FROM `m_chain`, `pool`
    WHERE `m_chain`.`morpheme_id`=`pool`.`id`
    AND `m_chain`.`word_id` = '" . $wordId ."'
    ORDER BY `m_chain`.`word_id` ASC";
    return array_values($this->ocunDataBase->query($sql)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC));
  }

  //frase por morfema (forma, significado)
  public function morphemeInSentence($form, $meaning){
    $sql = "SELECT DISTINCT `w_chain`.`phrase_id` AS `id`, `phrase`.`translation` AS `translation`,
    `w_chain`.`word_id` AS `word_id`, `m_chain`.`morpheme_id` AS `morpheme_id`, `pool`.`form` AS `form`,
    `pool`.`meaning` AS `meaning`, `phrase`.`source_id` AS `source_id` FROM `phrase`, `w_chain`, `m_chain`, `pool`
    WHERE `phrase`.`source_id` = '" . $this->sourceID . "'
    AND `w_chain`.`phrase_id` = `phrase`.`id`
    AND `w_chain`.`word_id` = `m_chain` . `word_id`
    AND `m_chain`.`morpheme_id` = `pool`.`id`
    AND `pool`.`form` = '" . $form . "'
    AND `pool`.`meaning` = '" . $meaning . "'
    ORDER BY `phrase`.`id` ASC";
    return array_values($this->ocunDataBase->query($sql)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC));
  }


/* deprecated...
  //Retorna a tabela com palavras contendo o significado solicitado
  public function wordPattern($meaning){
    $sql = "SELECT DISTINCT `m_chain`.`word_id` AS `word`, `m_chain`.`word_id` AS `word`, `m_chain`.`ord` AS `order`,
    `pool`.`meaning` AS `meaning`, `pool`.`form` AS `form`
    FROM `m_chain`, `pool`
    WHERE `m_chain`.`morpheme_id`=`pool`.`id`
    AND `m_chain`.`word_id` IN
    (SELECT `m_chain`.`word_id`
      FROM `m_chain`,`pool`
      WHERE `m_chain`.`morpheme_id`=`pool`.`id`
      AND `pool`.`source_id`='".  $this->sourceID . "'
      AND `pool`.`meaning`='" . $meaning .  "')
    ORDER BY `m_chain`.`word_id` ASC";
    return array_values($this->ocunDataBase->query($sql)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC));
  }

  //Retorna a tabela com palavras contendo a forma fonológica solicitada
  public function realization($form){
    $sql = "SELECT DISTINCT `m_chain`.`word_id` AS `word`, `m_chain`.`word_id` AS `word`, `m_chain`.`ord` AS `order`,
    `pool`.`meaning` AS `meaning`, `pool`.`form` AS `form`
    FROM `m_chain`, `pool`
    WHERE `m_chain`.`morpheme_id`=`pool`.`id`
    AND `m_chain`.`word_id` IN
    (SELECT `m_chain`.`word_id`
      FROM `m_chain`,`pool`
      WHERE `m_chain`.`morpheme_id`=`pool`.`id`
      AND `pool`.`source_id`='".  $this->sourceID . "'
      AND `pool`.`form`='" . $form .  "')
    ORDER BY `m_chain`.`word_id` ASC";
    return array_values($this->ocunDataBase->query($sql)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC));
  }
  */


}




?>
