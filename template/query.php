<section id="source-panel">
  <nav id="language-select">
    <?php foreach($languageList as $language): ?>
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


<script type="text/javascript">
var languageList = <?= json_encode($languageList) ?>;
var sourceList = <?= json_encode($sourceList) ?>;

languageSelect(0);

function Ajax(action, resp){
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        resp(this.responseText);
    }
  };
  xmlhttp.open("GET", action, true);
  xmlhttp.send();
}

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
    document.getElementById("dashboard-panel").innerHTML = "";
    var functional = JSON.parse(resp);
    sentence = sentenceBuild(functional);
    for (i in sentence){
      document.getElementById("dashboard-panel").innerHTML += sentence[i];
    }
  }
}


function getAllomorph(id, meaning){
  Ajax("ajax.php?action=getAllomorph&id=" + id + "&meaning=" + meaning, myFunc);
  document.getElementById("current-action-title").innerHTML = "<h1>Morfemas com o significado \"" + meaning + "\"";
  document.getElementById("dashboard-panel").innerHTML = "carregando..."
  function myFunc(resp) {
    data = JSON.parse(resp);
    document.getElementById("dashboard-panel").innerHTML = "";
    for (i in data){
      str = "<button onclick='calculateMorphemeStats(" + id + ", " + JSON.stringify(data[i]) + ", 1)'>" + data[i].form + "</button>";
      document.getElementById("dashboard-panel").innerHTML += str;

    }
    document.getElementById("dashboard-panel").innerHTML += "<div id='statistics-1'></div>";
  }
}

function sentenceBuild(sentenceBase){
  var disp = [];
  var backgroundColor = ["#82b2b8", "#b88882"];
  var currentColor = 0;
  var wordId = 0;
  var str = "";
  function getColor(changeColor){
    if(changeColor){
      if (currentColor == (backgroundColor.length - 1)) {
        currentColor = 0;
      }
      else {
        currentColor++;
      }
    }
    return currentColor;
  }
  for(i in sentenceBase){
   for (j in sentenceBase[i]){
    if(sentenceBase[i][j].word_id == wordId){
      changeColor = false;
    }
    else {
      changeColor = true;
    }
    str += "<button style='background-color: " + backgroundColor[getColor(changeColor)] + "'" +
    "onclick='getAllomorph(" +
    sentenceBase[i][j].source_id +
    ", \"" +
    sentenceBase[i][j].meaning +
    "\")'>" +
    "<p>" + sentenceBase[i][j].form + "</p>" +
    "<p>" + sentenceBase[i][j].meaning + "</p>" +
    "</button></div>";
    wordId = sentenceBase[i][j].word_id;
    }
    str += "<br><button><p>" + sentenceBase[i][0].translation + "</p></button><br><br>"
    disp.push(str);
    str = "";

 }
 return disp;
}

function sentenceFilter(source, morpheme, span){
  Ajax("ajax.php?action=getSentence&id=" + source, display);
  document.getElementById("statistics-filter-" + span).innerHTML = "carregando...";
  function display(resp) {
    document.getElementById("statistics-filter-" + span).innerHTML = "";
    var functional = JSON.parse(resp);
    var filtered = [];
    for (i in functional){
      for (j in functional[i]){
        if (functional[i][j].morpheme_id == morpheme.id){
          filtered.push(functional[i]);
        }
      }
    }
    sentence = sentenceBuild(filtered);
    document.getElementById("statistics-filter-" + span).innerHTML = "<h1>Total de frases: " + sentence.length + "</h1>";
    for (let i = 0; i < sentence.length; i++){
      document.getElementById("statistics-filter-" + span).innerHTML += sentence[i];
    }
  }
}

function wordFilter(source, morpheme, span) {
  Ajax("ajax.php?action=getSentence&id=" + source, display);
  document.getElementById("statistics-filter-" + span).innerHTML = "carregando...";
  function display(resp) {
    var functional = JSON.parse(resp);
    var filtered = [];
    var wordIds = [];
    for (i in functional){
      for (j in functional[i]){
        if (functional[i][j].morpheme_id == morpheme.id){
          worddIds.push(functional[i][j].word_id);
        }
      }
    }

    console.log(filtered);
    sentence = sentenceBuild(filtered);
    for (let i = 0; i < sentence.length; i++){
      document.getElementById("statistics-filter-" + span).innerHTML += sentence[i];
    }
  }
}

function calculateMorphemeStats(source, morpheme, span){
  var morphemeMatch = 0;
  var morphemeCount = 0;
  var allomorph = 0;
  var homonym = 0;
  Ajax("ajax.php?action=getSentence&id=" + source, display);
  function display(resp) {
    var functional = JSON.parse(resp);
    for (i in functional){
      for (j in functional[i]) {
        morphemeCount++;
        if (functional[i][j].morpheme_id == morpheme.id) {
          morphemeMatch++;
        }
        if (functional[i][j].meaning == morpheme.meaning) {
          allomorph++;
        }
        if (functional[i][j].form == morpheme.form){
          homonym++;
        }
      }
    }
    document.getElementById("statistics-" + span).innerHTML = "<h1>Morfema #" + morpheme.id + "</h1>" +
    "<p><b>Forma fonológica: </b>" + morpheme.form + "</p>" +
    "<p><b>Significado: </b>" + morpheme.meaning + "</p>" +
    "<p>Algumas estatísticas: </p>" +
    "<ul>" +
    "<li>Ocorrências no corpus: " + morphemeMatch + "/" + morphemeCount + " ou " +  (morphemeMatch/morphemeCount) + "</li>" +
    "<li>Proporção entre alomorfes: " + morphemeMatch + "/" + allomorph + " ou " + (morphemeMatch/allomorph) + "</li>" +
    "<li>Proporção entre homonimos: " + morphemeMatch + "/" + homonym + " ou " + (morphemeMatch/homonym) + "</li>" +
    "</ul>" +
    "<button onclick='sentenceFilter(" + source + ", " + JSON.stringify(morpheme) + ", " + morpheme.id +")'>Frases com este morfema</button>" +
    "<button onclick='wordFilter(" + source + ", " + JSON.stringify(morpheme) + ", " + morpheme.id +")'>Palavras com este morfema</button>" +
    "<div id='statistics-filter-" + morpheme.id + "'></div>";

  }
}


</script>
