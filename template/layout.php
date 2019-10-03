<!doctype html>
<html>
<head>
  <link href="https://fonts.googleapis.com/css?family=Montserrat:500,500i,700&display=swap&subset=latin-ext" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700&display=swap&subset=latin-ext" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="css/main.css">
  <link rel="stylesheet" type="text/css" href="css/query.css">
  <title><?=$title?></title>
</head>
<body>
  <header>
    <div class='topnav'>
      <img src="svg/logo.svg">
      <a href="index.php">òcun</a>
      <div class='topnav-right'>
      <a href="logout.php">Sair</a>
    </div>
  </div>
  </header>
  <div id='content'>

  <?php include __DIR__ . "/../template/" . $page ?>

 </div>
 <footer>
   <p>Ocun v0.2.0 <!-- <a target="_blank" rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc/4.0/88x31.png" /></a> -->This work is licensed under a <a target="_blank" rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/">Creative Commons Attribution-NonCommercial 4.0 International License</a></p>
 </footer>
</body>
</html>
