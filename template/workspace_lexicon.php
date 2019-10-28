<h1><?=$language['name']?></h1>
<h2>Dicionário de Morfemas</h2>
<br>
<br>
<p>Total de morfemas: <b><?=count($morphemes)?></b></p>
<?php foreach($morphemes as $morpheme): ?>
  <p>
  <button class="button-sentence" onclick="Ajax('ajax.php?action=displayData&id=<?=$source_id?>&function=morpheme&form=<?=$morpheme['form']?>&meaning=<?=$morpheme['meaning']?>', displayAjaxDataInWorkspace)"><p><?=$morpheme['form']?></p><p><?=$morpheme['meaning']?></p></button>
  </p>
<?php endforeach; ?>
