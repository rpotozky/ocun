<h1>Selecione uma língua</h1>
<p>Abaixo estão relacionadas as línguas que fazem parte de nosso banco de dados. Escolha uma delas para iniciar a navegação! ⎈</p>

<div class='source-and-language'>
  <?php foreach($language_list as $language):?>
    <p><button onclick="Ajax('ajax.php?action=languageInfo&code=<?=$language['code']?>', displayAjaxDataInWorkspace)"><?=$language['name']?></button></p>

    <!-- <p><button onclick="showLanguageInfo('<?=$language['code']?>','<?=$language['name']?>')"><?=$language['name']?></button></p> -->
  <?php endforeach;?>
</div>
