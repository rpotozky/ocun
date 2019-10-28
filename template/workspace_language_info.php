<h1><?=$language['name']?></h1>

<div class="sentence">
  <h2>Informações:</h2>
  <p><b>Região: </b><?=$language['region']?><br>
    <b>Número de Falantes: </b><?=$language['speakers']?></p>
</div>

<div class="source-and-language">
<h2>Fontes: </h2>
<?php foreach($sources as $source): ?>
  <div onmouseover="view.workspace[view.activeWorkspace].source=<?=$source['id']?>" style="background-color: white; border-radius: 10px; padding: 10px;">
    <h2><?=$source['title']?></h2>
    <p><b>Autor: </b><?=$source['author']?><br>
    <em>Publicado em <?=$source['year']?> por <?=$source['publisher']?></em></p>
    <p><b><?=$source['license']?></b>, <a href="<?=$source['url']?>" target="_blank">link para o trabalho</a></p>
    <h2>Menu: </h2>
    <ul>
      <li><button onclick="Ajax('ajax.php?action=displayData&id=<?=$source['id']?>&function=sentence', displayAjaxDataInWorkspace)">Frases da Língua</button></li>
      <li><button onclick="Ajax('ajax.php?action=displayData&id=<?=$source['id']?>&function=functional', displayAjaxDataInWorkspace)">Morfemas por Função</button></li>
      <li><button onclick="Ajax('ajax.php?action=displayData&id=<?=$source['id']?>&function=lexicon', displayAjaxDataInWorkspace)">Dicionário de Morfemas</button></li>
    </ul>




  </div>
<?php endforeach;?>

</div>
