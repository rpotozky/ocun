<section id="source-panel">
  <h1>Consulta aos dados☁ </h1>
  <p>Selecione a língua abaixo: </p>
  <nav id="language-select">
    <?php foreach($languageAndSourceList as $language): ?>
      <button class="button-language-select" onclick="languageSelect('<?=$language['code']?>')"><?= $language['name'] . " - " . $language['code']?></button>
    <?php endforeach; ?>
  </nav>
  <article id="language-info">
    <h1 id="info-name"></h1>
    <p><b>Número de falantes:</b> <span id="info-speakers"></span></p>
    <p><b>Região:</b> <span id="info-region"></span></p>
    <h2>Fontes: </h2>
    <div id="language-sources">

    </div>
  </article>
  <article class="query-dashboard">
    <div>
        <h1 id="current-action-title"></h1>
    </div>
    <div id="dashboard-panel">
    </div>
  </article>
</section>

<script type="text/javascript" src="js/sentence.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/stats.js"></script>
<script type="text/javascript">
var languageList = <?= json_encode($languageAndSourceList) ?>;
console.log(languageList);
var sourceList = <?= json_encode($sourceList) ?>;

languageSelect(0);

function languageSelect(language){
  if (language == 0){
    document.getElementById("language-info").style.display = "none";
  } else {
    document.getElementById("language-info").style.display = "block";
    document.getElementById("current-action-title").innerHTML = "";
    document.getElementById("dashboard-panel").innerHTML = "";
    for (i in languageList) {
      if (languageList[i].code == language) {
        document.getElementById("info-name").innerHTML = languageList[i].name;
        document.getElementById("info-speakers").innerHTML = languageList[i].speakers;
        document.getElementById("info-region").innerHTML = languageList[i].region;
        for (j in sourceList) {
          if (sourceList[j].language_code == language){
            document.getElementById("language-sources").innerHTML = "<p><b>Autor: </b>" + sourceList[j].author +
            "</p><p><b>Título:</b> " + sourceList[j].title +
            "</p><p><em>Publicado em " + sourceList[j].year + " por " + sourceList[j].publisher + "</em></p>" +
            "<div id='source-buttons'>" +
            "<button class='button-source-select' onclick='FunctionalDisplay(" + sourceList[j].id + ")'>Morfemas Funcionais</button>" +
            "<button class='button-source-select' onclick='RootDisplay(" + sourceList[j].id + ")'>Morfemas Lexicais</button>" +
            "<button class='button-source-select' onclick='SentenceDisplay(" + sourceList[j].id + ")'>Frases</button>" +
            "</div>";
          }
        }
      }
    }
  }
}

function FunctionalDisplay(source){
  Ajax("ajax.php?action=getFunctional&id=" + source, display);
  document.getElementById("current-action-title").innerHTML = "Categorias Funcionais Descritas: ";
  document.getElementById("dashboard-panel").innerHTML = "carregando..."
  function display(resp) {
    var functional = JSON.parse(resp);
    document.getElementById("dashboard-panel").innerHTML = "<p>Abaixo estão relacionadas as categorias funcionais/inventário fechado da língua conforme descrito pelo autor da gramática.</p><p>Ao clicar em cada categoria você pode obter os morfemas que instanciam essa categoria, acompanhado da frequência com que ocorrem na amostra (dados da gramática): </p>";
    for (i in functional) {
      str = "<div class='functional-dictionary-entry'><button onclick='getAllomorph(" +
      functional[i].source_id +
      ", \"" +
      functional[i].abbreviation +
      "\")'>" +
      functional[i].meaning +
      " (<b>" +
      functional[i].abbreviation +
      "</b>)</button></div>";
      document.getElementById("dashboard-panel").innerHTML += str;
    }
  }
}


function RootDisplay(source){
  Ajax("ajax.php?action=getRoot&id=" + source, display);
  document.getElementById("current-action-title").innerHTML = "Morfemas Lexicais";
  document.getElementById("dashboard-panel").innerHTML = "carregando..."
  function display(resp) {
    var functional = JSON.parse(resp);
    document.getElementById("dashboard-panel").innerHTML = "<p>Abaixo estão relacionados os morfemas lexicais da língua (raízes):</p>";
    for (i in functional) {
      str = "<div class='functional-dictionary-entry'><button onclick='getAllomorph(" +
      functional[i].source_id +
      ", \"" +
      functional[i].meaning +
      "\")'>" +
      functional[i].meaning +
      " (<b>" +
      functional[i].form +
      "</b>)</button></div>";
      document.getElementById("dashboard-panel").innerHTML += str;
    }
  }
}



function SentenceDisplay(source){
  Ajax("ajax.php?action=getSentence&id=" + source, display);
  document.getElementById("current-action-title").innerHTML = "Frases na Língua";
  document.getElementById("dashboard-panel").innerHTML = "carregando..."
  function display(resp) {
    var functional = JSON.parse(resp);
    sentence = new SentenceBuild(functional);
    document.getElementById("dashboard-panel").innerHTML = "<h2>Total de frases: " + sentence.datasize + "</h2>";
    showSentence = function (){
      sentence.next();
      for (i in sentence.disp){
        document.getElementById("dashboard-panel").innerHTML += sentence.disp[i];
      }
      if (sentence.getRemainingNumber() > 0) {
        document.getElementById("dashboard-panel").innerHTML += "<button onclick=showSentence()>Restam mais <b>" + sentence.getRemainingNumber() + "</b> frases. Clique aqui para carregar mais!</button><br><br>";
      }
    };
    showSentence();
  }
}


</script>
