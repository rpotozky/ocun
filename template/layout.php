<!doctype html>
<html>
<head>
  <meta property="og:image" content="img/ocun_tile.png" />
  <meta property="og:title" content="Òcun" />
  <meta property="og:description" content="Plataforma de dados linguísticos" />
  <link href="https://fonts.googleapis.com/css?family=Montserrat:500,500i,700&display=swap&subset=latin-ext" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700&display=swap&subset=latin-ext" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/main.css">
  <link rel="stylesheet" type="text/css" href="css/query.css">
  <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
  <title><?=$title?></title>
</head>
<body>
  <header>
    <div class='topnav'>
      <img src="svg/logo.svg">
      <a href="index.php">òcun</a>
      <div class='topnav-right'>
        <?php if(isset($_SESSION['user'])): ?>
          <a href="logout.php">Sair</a>
        <?php endif; ?>
    </div>
  </div>
  </header>
  <div id='content'>

  <?php include __DIR__ . "/../template/" . $page ?>

 </div>
 <footer>
   <p>Ocun v0.2.Alpha</p>
 </footer>
</body>
</html>
