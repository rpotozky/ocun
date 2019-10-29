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
