<section id="register-main">
  <h1>Registre-se!</h1>
  <p>Para acessar a plataforma Òcun, pedimos que novos usuários se registrem, fornecendo seu email e nome.
    O registro é necessário para garantir a segurança dos dados e também para que os usuários manifestem
    sua concordância com os termos de uso.</p>
  <form id="register-form" action="index.php?action=registerUser" method="post">
    <input id="register-form-email" type="email" name="email"placeholder="informe seu email"  onfocusout="CheckEmail(this.value)" required>
    <p id="email-status"></p>
    <input id="register-form-password" type="password" placeholder="digite sua senha" required>
    <input id="register-form-password-retype" type="password" name="password" onfocusout="CheckPassword(this.value)" placeholder="confirme sua senha" required>
    <p id="password-status"></p>
    <input type="text" name="name" placeholder="informe seu nome completo" onkeyup="CheckName(this.value)" style="width: 400px;" required>
    <input id="register-form-submit" type="submit" value="Registrar">
  </form>
</section>

<script>
var ckEmail = false;
var ckPassword = false;
var ckName = false;

function EvalSubmit() {
  if (ckEmail && ckPassword && ckName) {
    document.getElementById("register-form-submit").disabled = false;
  }
  else {
    document.getElementById("register-form-submit").disabled = true;
  }
}

function CheckEmail(str) {
  if (str.length == 0) {
    document.getElementById("email-status").innerHTML = "";
  }
  else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        if(this.responseText == "false"){
          document.getElementById("email-status").innerHTML = "Email já cadastrado. Por favor escolha outro!";
          ckEmail = false;
        }
        else {
          document.getElementById("email-status").innerHTML = "";
          ckEmail = true;
        }
      }
    };
    xmlhttp.open("GET", "ajax.php?action=checkEmailDoesNotExist&email=" + str, true);
    xmlhttp.send();
  }
  EvalSubmit();
}

function CheckPassword(str) {
  if (str.length > 0 && str == document.getElementById("register-form-password").value){
    ckPassword = true;
    document.getElementById("password-status").innerHTML = "";
  }
  else if (str.length > 0 && str != document.getElementById("register-form-password").value){
    ckPassword = false;
    document.getElementById("password-status").innerHTML = "As senhas não estão batendo. Por favor verifique.";
  } else {
    ckPassword = false;
  }
  EvalSubmit();
}

function CheckName(str) {
  if (str.length > 0) {
    ckName = true;
  }
  else {
    ckName = false;
  }
  EvalSubmit();
}

EvalSubmit();

</script>
