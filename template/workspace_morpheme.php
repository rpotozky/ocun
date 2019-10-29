<h1>
  <div style="display: inline-block; background-color: #7eadba; border-radius: 10px; padding: 10px; ">
    <h1 style="text-align: center;"><?=htmlentities($form)?></h1>
    <h1 style="text-align: center;"><?=htmlentities($meaning)?></h1>
  </div> Morfema do <?=$language?>
</h1>


<div style="display: block; margin: 20px; background-color: #AABBCC; border-radius: 10px; padding: 10px; ">
  <p><b>Dados morfológicos do <?=$language?></b></p>
  <p>Número de morfemas no corpus: <b><?=$stats['morpheme_count']?></b>
    <br> Média de morfemas por frase: <b><?=$stats['morpheme_count']/$sentence_count?></b>
    <br> Morfemas distintos por tamanho do corpus (<?=count($stats['morpheme_list'])."/".$stats['morpheme_count']?>): <b><?=count($stats['morpheme_list'])/$stats['morpheme_count']?></b></p>
</div>

<?php if(count($functional_meaning) > 0): ?>
  <table>
    <caption><b>Significados Funcionais</b></caption>
    <tr>
      <th><b>Abreviação</b></th>
      <th><b>Significado</b></th>
    </tr>
    <?php foreach($functional_meaning as $abbvr): ?>
      <tr>
        <td><?=$abbvr['abbreviation']?></td>
        <td><?=$abbvr['meaning']?></td>
      </tr>
    <?php endforeach;?>
  </table>
  <br>
<?php endif;?>




<table>
  <caption><b>Dados Probabilísticos do Morfema</b></caption>
  <tr>
    <th></th><th><b>Contagem</b></th><th><b>P</b></th><th><b>-LogP</b></th>
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

<?php include __DIR__ . '/../template/workspace_sentence_builder.php';?>
