<h1>
  <div style="display: inline-block; background-color: #7eadba; border-radius: 10px; padding: 10px; ">
    <h1 style="text-align: center;"><?=$form?></h1>
    <h1 style="text-align: center;"><?=$meaning?></h1>
  </div> Morfema do <?=$language?></h1>

  <div style="display: block; margin: 20px; background-color: #AABBCC; border-radius: 10px; padding: 10px; ">
    <p><b>Dados morfológicos do <?=$language?></b></p>
    <p>Número de morfemas no corpus: <b><?=$stats['morpheme_count']?></b> <br> Média de morfemas por frase: <b><?=$stats['morpheme_count']/$sentence_count?></b></p>
  </div>

<table>
  <caption><b>Dados Probabilísticos do Morfema</b></caption>
  <tr>
    <th></th><th>Contagem</th><th>P</th><th>-logP</th>
  </tr>
  <tr>
    <td>Morfema</th><th><?=$stats['morpheme_match']?></th><th><?=$stats['morpheme_probability']?></th><th><?=$stats['morpheme_logP']?></th>
  </tr>
  <tr>
    <td>Significado (<?=$meaning?>)</th><th><?=$stats['meaning_match']?></th><th><?=$stats['meaning_probability']?></th><th><?=$stats['meaning_logP']?></th>
  </tr>
  <tr>
    <td>Forma (<?=$form?>)</th><th><?=$stats['form_match']?></th><th><?=$stats['form_probability']?></th><th><?=$stats['form_logP']?></th>
  </tr>
</table>
<br>

<?php if (count($allomorphs) > 1): ?>
  <h2>Alomorfes</h2>
  <p>Número de alomorfes: <?= count($allomorphs) - 1?></p>
    <div class="sentence">
      <?php foreach($allomorphs as $allomorph): ?>
          <button class="button-sentence" onclick="Ajax('ajax.php?action=displayData&id=<?=$source_id?>&function=morpheme&form=<?=$allomorph['form']?>&meaning=<?=$allomorph['meaning']?>', displayAjaxDataInWorkspace)"><p><?=$allomorph['form']?></p><p><?=$allomorph['meaning']?></p></button>
      <?php endforeach;?>
    </div>
  <br>
<?php endif;?>

<?php if (count($homonyms) > 1): ?>
  <h2>Homônimos</h2>
  <p>Número de homônimos: <?= count($homonyms) - 1?></p>
    <div class="sentence">
      <?php foreach($homonyms as $homonym): ?>
          <button class="button-sentence" onclick="Ajax('ajax.php?action=displayData&id=<?=$source_id?>&function=morpheme&form=<?=$homonym['form']?>&meaning=<?=$homonym['meaning']?>', displayAjaxDataInWorkspace)"><p><?=$homonym['form']?></p><p><?=$homonym['meaning']?></p></button>
      <?php endforeach;?>
    </div>
  <br>
<?php endif;?>

<h2>Frases com o morfema</h2>
<p>Número de frases: <b><?= $stats['sentence_count'] ?></b></p>

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
