<?php foreach($sentences as $sentence): ?>
  <div class="sentence">
    <?php foreach($sentence['morphemes'] as $morpheme): ?>
      <?php if(!isset($morpheme['meaning'])): ?>
        <span>&nbsp;</span>
      <?php else: ?>
        <button class="button-sentence" onclick="Ajax('ajax.php?action=displayData&id=<?=$source_id?>&function=morpheme&form=<?=$morpheme['form']?>&meaning=<?=$morpheme['meaning']?>', displayAjaxDataInWorkspace)"><p><?=$morpheme['form']?></p><p><?=$morpheme['meaning']?></p></button>
      <?php endif; ?>
    <?php endforeach;?>
    <p class="sentence-translation">"<?=$sentence['translation']?>"</p>
    <button class="button-send-to-notes" onclick="view.addToNotes(`
${'<?=$language?>'}
${'<?=$sentence['text']['original']?>'}
${'<?=$sentence['text']['gloss']?>'}
${'<?=$sentence['text']['translation']?>'}
`)"><p>Notas</p></button>
    <button class="button-send-to-notes" onclick="console.log(`
${'<?=$language?>'}
${'<?=$sentence['text']['original']?>'}
${'<?=$sentence['text']['gloss']?>'}
${'<?=$sentence['text']['translation']?>'}
`)"><p>Console</p></button>
  </div>
<?php endforeach; ?>
