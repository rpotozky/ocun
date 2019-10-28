<?php

class OcunController implements OcunControllerInterface {

  private $ocunException;

  public function __construct(OcunException $ocunException) {
    $this->ocunException = $ocunException;
  }


  //template loader
  private function loadTemplate($templateFileName, $variables = []){
    extract($variables);
    ob_start();
    include __DIR__ . '/../template/' . $templateFileName;
    return ob_get_clean();
  }

  //Welcome page, for unregistered or not logged users.
  public function welcome() {
    //check if authenticated (then open home)
    if (isset($_SESSION['user'])) {
      header('Location: index.php?action=home');
    }
    else {
      //display authentication error message when needed
      $authStatus = "";
      if (isset($_GET['msg']) && $_GET['msg'] == 'authErr') {
        $authStatus = 'Falha de autenticação. Verifique usuário e senha...';
      }
      return $this->loadTemplate("layout.php", [
        'page' => 'welcome.php',
        'title' => 'Bem-vindo à plataforma Òcun!',
        'authStatus' => $authStatus
      ]);
    }
  }

  //User login
  public function signIn() {
    if (isset($_POST['user']) && isset($_POST['password'])){
      $userDB = new OcunUserDataBase($this->ocunException);
      if ($userDB->authenticate($_POST['user'], $_POST['password'])){
        session_start();
        $_SESSION['user'] = $_POST['user'];
        header('Location: index.php');
      }
      else {
        header('Location: index.php?msg=authErr');
      }
    }
    else {
      header('Location: index.php?msg=authErr');
    }

  }

  //User registration page
  public function signUp() {
    return $this->loadTemplate("layout.php", [
      'page' => 'register.php',
      'title' => 'Registro'
    ]);
  }

  //User registration action
  public function registerUser() {
    if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['name'])){
      $userDB = new OcunUserDataBase($this->ocunException);
      $userDB->create($_POST['email'], $_POST['password'], $_POST['name']);
      return $this->loadTemplate("registration_successful.php");
    }
    else {
      return $this->loadTemplate("fail.php");
    }
  }

  //All the methods below must require authentication

  //Home page, for logged users.
  public function home() {
    if (isset($_SESSION['user'])) {
      return $this->loadTemplate("layout.php",[
        'page' => "home.php",
        'title' => "òcun - " . $_SESSION['user']
      ]);
    }
    else {
      header('Location: index.php');
    }
  }

  //Load app for managing data sources (grammars)
  public function manageSource() {
    if (isset($_SESSION['user'])) {
      return $this->loadTemplate("source.php");
    }
    else {
      header('Location: index.php');
    }
  }

  //Loads app for inserting linguistic data
  public function insertData() {
    if (isset($_SESSION['user'])) {
      return $this->loadTemplate("insert.php");
    }
    else {
      header('Location: index.php');
    }
  }


}









 ?>
