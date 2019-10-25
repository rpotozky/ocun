<section id="query-menu">
  <button onclick="view.addWorkspace()">&plus; Adicionar área de trabalho </button>
  <span id="load-status"></span>
</section>
<section>
  <div class="flex-container" id="query-workspace">
  </div>
</section>
<script type="text/javascript" src="js/ajax.js">//ajax functions</script>
<script type="text/javascript" src="js/view.js">//view object</script>
<script type="text/javascript" src="js/display.js">//mostly callback functions</script>
<script type="text/javascript" src="js/stats.js">//statistics</script>
<script type="text/javascript" src="js/stomo.js">//statistics</script>
<script type='text/javascript'>
// Important variables
var languageList = <?= json_encode($languageList) ?>;
var sourceList = <?= json_encode($sourceList) ?>;

//Display Content:



function showLanguageInterface(){
  view.setWorkspaceBufferIndex();
  str = "<h1>Selecione uma língua</h1>" +
  "<p>Abaixo estão relacionadas as línguas que fazem parte de nosso banco de dados. Escolha uma delas para iniciar a navegação! ⎈";
  languageList.forEach((el) => {
    str += `<p><button onclick="showLanguageInfo('${el.code}','${el.name}')">${el.name}</button></p>`;
  });
  view.setWorkspaceContent(str);
}

function showLanguageInfo(code, lname){
  view.setWorkspaceBufferIndex();
  view.setWorkspaceLanguage(lname);
  sources = sourceList.filter((el) =>{
    return el.language_code == code;
  });
  languageList.forEach((el) => {
    if (el.code == code) {
      str = `<h1>${el.name}</h1><p><b>Região:</b> ${el.region}</p><p><b>Falantes:</b> ${el.speakers}</p>`;
      str += `<h2>Fontes: </h2>`;
      sources.forEach((el) => {
        /*Ajax(`ajax.php?action=getSentence&id=${el.id}`, languageEntropy);*/
        str += `<div onmouseover='view.workspace[view.activeWorkspace].source=${el.id}' class='source-list'><p><b>Autoria:</b> ${el.author}</p>`;
        str += `<p><b>Título:</b> ${el.title}</p>`;
        str += `<p><em>Publicado em ${el.year} por ${el.publisher}</em></p>`;
        str += `<p> ${el.license} </p>`;
        str += `<button onclick="ajaxQuery(${el.id},['ajax.php?action=getFunctional&id=${el.id}', displayFunctional])">Significados Funcionais</button>`;
        str += `<button onclick="ajaxQuery(${el.id},['ajax.php?action=getRoot&id=${el.id}', displayLexical])">Raízes</button>`;
        // str += `<button onclick="ajaxQuery(${el.id},['ajax.php?action=getSentence&id=${el.id}', displaySentence])">Frases</button><br><br>`;
        str += `<button onclick="Ajax('ajax.php?action=displayData&id=${el.id}&function=sentence', displayAjaxDataInWorkspace)">Frases</button><br><br>`;
        str += `<p><b>Análises Estatísticas (.JSON)</b></p>`;
        str += `<p><em>Os arquivos podem demorar para serem processados</em></p>`;
        str += `<button onclick="Ajax('ajax.php?action=getSentence&id=${el.id}>', stomo.receive)">Análise Avançada (2a Ordem)</button></div>`;
      })
    }
    view.setWorkspaceContent(str);
  });
}
</script>
