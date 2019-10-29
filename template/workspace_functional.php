<h1><?=$language?></h1>
<h2>Morfemas por função</h2>
<br>
<br>
<?php foreach($functional as $function): ?>
  <div class="source-and-language">
  <h2>(<?=$function[0]['abbreviation']?>) - <?=$function[0]['fmeaning']?></h2>
  <div style="background-color: white; border-radius: 10px; padding: 10px">
    <?php foreach($function as $morpheme): ?>
      <button class="button-sentence" onclick="Ajax('ajax.php?action=displayData&id=<?=$source_id?>&function=morpheme&form=<?=$morpheme['form']?>&meaning=<?=$morpheme['meaning']?>', displayAjaxDataInWorkspace)"><p><?=$morpheme['form']?></p><p><?=$morpheme['meaning']?></p></button>
    <?php endforeach; ?>
  </div>
</div>

<?php endforeach;?>
