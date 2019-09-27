<!-- This is a test page -->

<?php

function my_autoload ($pClassName) {
    include(__DIR__ . "/../class/" . $pClassName . ".php");
}
spl_autoload_register("my_autoload");

$ex = new OcunException();
$db = new OcunDataBase($ex);
$qu = new OcunQuery($db, 3);

$sentence = $qu->sentence();
$functional = $qu->functional();
$root = $qu->root();

?>

<div id="sentence_list">
  <p>Sentences List: </p>

</div>

<script>
  const sentence = <?= $sentence ?>;

  console.log(sentence);
  console.log(<?= $qu->wordPattern("1") ?>);
  console.log(<?= $qu->realization("he") ?>);

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
