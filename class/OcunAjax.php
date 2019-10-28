<?php

class OcunAjax implements OcunAjaxInterface{

  private $ocunException;

  private $sourceRestriction = "";

  public function __construct(OcunException $ocunException) {
    $this->ocunException = $ocunException;
  }


  //Template loader
  private function loadWorkspaceTemplate($templateFileName, $variables = []){
    extract($variables);
    ob_start();
    include __DIR__ . '/../template/' . $templateFileName;
    return ob_get_clean();
  }

  //Lista as línguas. Por enquanto, apenas línguas públicas e do grupo são listadas...
  public function languageList(){
    $ocunDataBase = new OcunDataBase($this->ocunException);
    session_start();

    // SQL para selecionar línguas considerando restrições de grupo
    $languageList = $_SESSION['user'] == 'root@ocun.latip.com.br' ? $ocunDataBase->query("SELECT *
    FROM `language`
    ORDER BY `language`.`name`") : $ocunDataBase->query("SELECT *
      FROM `language`
      WHERE `language`.`code`
      IN (
        SELECT `language_code`
        FROM `source`
        WHERE `source`.`shared` = 'public' OR
        `source`.`team` IN (
          SELECT `latip_user`.`team`.`id`
          FROM `latip_user`.`user`, `latip_user`.`team`, `latip_user`.`team_membership`
          WHERE `latip_user`.`user`.`email` = '".$_SESSION['user']."' AND
          `latip_user`.`user`.`id` = `latip_user`.`team_membership`.`user_id` AND
          `latip_user`.`team`.`id` = `latip_user`.`team_membership`.`team_id`))
      ORDER BY `language`.`name`")->fetchAll(PDO::FETCH_ASSOC);


    return $this->loadWorkspaceTemplate('workspace_language_list.php', ['language_list' => $languageList]);
  }

  public function languageInfo(){
    if (isset($_GET['code'])){
      session_start();
      $ocunDataBase = new OcunDataBase($this->ocunException);
      $language = $ocunDataBase->query("SELECT * FROM `language` WHERE `language`.`code` = '".$_GET['code']."'")->fetch(PDO::FETCH_ASSOC);

      // SQL para selecionar fontes considerando restrições de grupo
      $sourceList = $_SESSION['user'] == 'root@ocun.latip.com.br' ? $ocunDataBase->query("SELECT *
      FROM `source` WHERE `source`.`language_code` = '".$_GET['code']."'") : $ocunDataBase->query("SELECT *
        FROM `source`
        WHERE `source`.`language_code`= '".$_GET['code']."' AND
        (`source`.`shared` = 'public' OR
        `source`.`team` IN (
          SELECT `latip_user`.`team`.`id`
          FROM `latip_user`.`user`, `latip_user`.`team`, `latip_user`.`team_membership`
          WHERE `latip_user`.`user`.`email` = '".$_SESSION['user']."' AND
          `latip_user`.`user`.`id` = `latip_user`.`team_membership`.`user_id` AND
          `latip_user`.`team`.`id` = `latip_user`.`team_membership`.`team_id`))")->fetchAll(PDO::FETCH_ASSOC);


      return $this->loadWorkspaceTemplate('workspace_language_info.php', ['language' => $language, 'sources' => $sourceList]);
    }
    return $this->loadWorkspaceTemplate('workspace_error.php', []);
  }

  public function displayData(){
    if (isset($_GET['id']) && isset($_GET['function'])) {
      $function = $_GET['function'];
      $ocunDataBase = new OcunDataBase($this->ocunException);
      $ocunQuery = new OcunQuery($ocunDataBase, $_GET['id']);
      $ocunDataDisplayer = new OcunDataDisplayer($ocunQuery);
      $displayData = $ocunDataDisplayer->$function();
      return $this->loadWorkspaceTemplate($displayData['template'], $displayData['variables']);
    }
    return $this->loadWorkspaceTemplate('workspace_error.php', []);
  }



  public function checkEmailDoesNotExist() {
    if (isset($_GET['email'])) {
      $db = new OcunUserDataBase($this->ocunException);
      $qu = "SELECT `email` FROM `user` WHERE `email` = '" . $_GET['email'] . "'";
      if(!$db->fetch($qu)) {
        return "true";
      }
    }
    return "false";
  }

  public function getUserNotes() {
    session_start();
    if (isset($_SESSION['user'])) {
      $db = new OcunUserDataBase($this->ocunException);
      $qu = "SELECT `notes` FROM `user` WHERE `email` = '" . $_SESSION['user'] . "'";
      return json_encode($db->fetch($qu));
    }
    return json_encode(["fail"]);
  }

  public function setUserNotes() {
    session_start();
    if (isset($_SESSION['user']) && isset($_POST['notes'])) {
      $db = new OcunUserDataBase($this->ocunException);
      $db->saveNotes($_SESSION['user'], $_POST['notes']);
      return json_encode(["success"]);
    }
    return json_encode(["fail"]);
  }

/*
  public function getFunctional() {
    if (isset($_GET['id'])) {
      $db = new OcunDataBase($this->ocunException);
      $qu = new OcunQuery($db, $_GET['id']);
      return json_encode($qu->functional());
    }
  }

  public function getRoot() {
    if (isset($_GET['id'])) {
      $db = new OcunDataBase($this->ocunException);
      $qu = new OcunQuery($db, $_GET['id']);
      return json_encode($qu->root());
    }
  }

  public function getSentence() {
    if (isset($_GET['id'])) {
      $db = new OcunDataBase($this->ocunException);
      $qu = new OcunQuery($db, $_GET['id']);
      return json_encode($qu->sentence());
    }
  }

  public function getSentenceAndWord() {
    if (isset($_GET['id'])) {
      $db = new OcunDataBase($this->ocunException);
      $qu = new OcunQuery($db, $_GET['id']);
      return json_encode([
        'sentences' => $qu->sentence(),
        'words' => $qu->word()
      ]);
    }
  }

  public function getAllFixedData() {
    if (isset($_GET['id'])) {
      $db = new OcunDataBase($this->ocunException);
      $qu = new OcunQuery($db, $_GET['id']);
      return json_encode([$qu->functional(), $qu->root(), $qu->sentence()]);
    }
  }

  public function getAllomorph() {
    if (isset($_GET['meaning']) && isset($_GET['id'])) {
      $db = new OcunDataBase($this->ocunException);
      $qu = new OcunQuery($db, $_GET['id']);
      return json_encode($qu->allomorph($_GET['meaning']));
    }
  }

*/

}






 ?>
