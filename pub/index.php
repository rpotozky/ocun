<!-- This is a test page -->

<?php

function my_autoload ($pClassName) {
    include(__DIR__ . "/../class/" . $pClassName . ".php");
}
spl_autoload_register("my_autoload");

$ex = new OcunException();
$db = new OcunDataBase($ex);
$qu = new OcunQuery($db, 7);

$sentence = $qu->sentence();

?>

<script>
  const sentence = <?= $sentence ?>;
  var idbs = 22;
  function groupID(obj) {
    if (obj.id == idbs){
      return obj;
    }
  }
  var pen = sentence.filter(groupID);


  console.log(pen);
  console.log(sentence);
</script>
