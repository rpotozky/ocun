<!DOCTYPE html>
<html>
  <head>
    <title>Bem-vindo à plataforma Òcun</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:500,500i,700&display=swap&subset=latin-ext" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/query.css">
  </head>
<body>
  <header>
    <div class='topnav'>
      <img src="svg/logo.svg">
      <a href="index.php">òcun</a>
      <div class='topnav-right'>
    </div>
  </header>
  <section id="welcome-text">
    <h1>Bem-vindo à plataforma <em>òcun</em>!</h1>
    <p>A plataforma <em>òcun</em> foi desenvolvida pelo <a target="_blank" href="https://www.latip.com.br">
      Laboratório de Tipologia Linguística</a> da <a target="_blank" href="http://www.ufba.br">Universidade Federal da Bahia</a>
      como uma forma de facilitar o acesso do linguista aos dados contidos em gramáticas descritivas de diferentes línguas. </p>
    <p>Muitas funcionalidades ainda estão por vir e novas gramáticas estão sendo adicionadas ao nosso banco de dados.
      Seja bem-vindo ao oceano dos dados linguísticos!</p>
  </section>
  <section id="welcome-login">
    <h1>Acesse a plataforma</h1>
    <form action="index.php?action=signIn" method="post">
      <input type="email" name="user" placeholder="digite seu e-mail" required>
      <input type="password" name="password" placeholder="digite sua senha" required>
      <p><?= $authStatus ?></p>
      <input type="submit" value="entrar">
    </form>
    <p>Novo por aqui? <a href="index.php?action=signUp">Registre-se!</a></p>
  </section>

  <footer>
    <p>Ocun v0.2.0</p>
  </footer>
</body>
</html>
