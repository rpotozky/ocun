<!-- This is a test page -->

<?php

// autoload
function my_autoload ($pClassName) {
    include(__DIR__ . "/../class/" . $pClassName . ".php");
}
spl_autoload_register("my_autoload");


// load required objects
$exception = new OcunException();
//$database = new OcunDataBase($ex);
//$query = new OcunQuery($db, 3);
$controller = new OcunController($exception);

// start session
session_start();

// load templates
$action = $_GET['action'] ?? 'welcome';
echo $controller->$action();



?>

<script>

  /*
  for(var i = 0; i < sentence.length; i++){
    document.getElementById("sentence_list").innerHTML += "<p style='border-style: solid; margin: 6px;'>";
    var str = "";
    for (var j = 0; j < sentence[i].length; j++){
      str += "<button><p>" + sentence[i][j].form + "</p><p>" + sentence[i][j].meaning + "</p></button>";
    }
    document.getElementById("sentence_list").innerHTML += str + "</p>" +
    "<p><button>" + sentence[i][0].translation + "</button></p>";
  } */

</script>
