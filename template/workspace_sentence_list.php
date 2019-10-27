<h1>Frases em <b><?= $language ?></b></h1>

<p>Número de frases: <b><?= $sentence_count ?></b></p>

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
    <button class="button-send-to-notes" onclick="view.addToNotes(`<br><?=$sentence['text']['original']?><br><?=$sentence['text']['gloss']?><br><?=$sentence['text']['translation']?><br>`)"><p>Notas</p></button>
  </div>
<?php endforeach; ?>
