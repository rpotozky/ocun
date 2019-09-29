<!DOCTYPE html>
<html>
  <head>
    <title>Bem-vindo à plataforma Òcun</title>
  </head>
<body>
  <header>
      <img src="svg/logo.svg">
      <p>òcun</p>
  </header>
  <body>
    <section id="welcome-text">
      <h1>Bem-vindo à plataforma <em>òcun</em>!</h1>
      <p>A plataforma <em>òcun</em> foi desenvolvida pelo <a href="https://www.latip.com.br">
        Laboratório de Tipologia Linguística</a> da <a href="http://www.ufba.br">Universidade Federal da Bahia</a>
        como uma forma de facilitar o acesso do linguista aos dados contidos em gramáticas descritivas de diferentes línguas.
        O sistema se baseia na análise feita pelos descritivistas, organizando as entradas presentes nas gramáticas em um
        banco de dados relacional. Essa organização permite que o usuário da plataforma tenha acesso a toda a rede de relações
        que um morfema ou uma palavra apresentam na língua, conforme a descrição gramatical. Com isso o usuário pode rapidamente
        obter informações sobre o léxico da língua, alomorfias, sistemas de caso e concordância, entre inúmeros outros aspectos
        morfossintáticos e semânticos. Além disso, ferramentas para análise probabilística estão em impementação, possibilitando
        que o usuário possa utilizar a plataforma para o desenvolvimento de ciência de dados linguísticos. </p>
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
  </body>
  <footer>
    <p>Ocun v0.2.0</p>
    <a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc/4.0/88x31.png" /></a><br />This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/">Creative Commons Attribution-NonCommercial 4.0 International License</a>
  </footer>
</body>
</html>
