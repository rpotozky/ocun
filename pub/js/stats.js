function getAllomorph(id, meaning){
  Ajax("ajax.php?action=getAllomorph&id=" + id + "&meaning=" + meaning, myFunc);
  document.getElementById("current-action-title").innerHTML = "<h1>Morfemas com o significado \"" + meaning + "\"<h1>";
  document.getElementById("dashboard-panel").innerHTML = "Carregando...";
  function myFunc(resp) {
    data = JSON.parse(resp);
    document.getElementById("dashboard-panel").innerHTML = "";
    for (i in data){
      str = "<button onclick='calculateMorphemeStats(" + id + ", " + JSON.stringify(data[i]) + ", 1)'>" +
      "<p>" + data[i].form + "</p>" +
      "<p>" + data[i].meaning + "</p>" +
      "</button> ";
      document.getElementById("dashboard-panel").innerHTML += str;

    }
    document.getElementById("dashboard-panel").innerHTML += "<div id='statistics-1'></div>";
  }
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
    sentence = new SentenceBuild(filtered);
    document.getElementById("statistics-filter-" + span).innerHTML = "<h2>Total de frases: " + sentence.datasize + "</h2>";
    showSentence = function (){
      sentence.next();
      for (i in sentence.disp){
        document.getElementById("statistics-filter-" + span).innerHTML += sentence.disp[i];
      }
      console.log(sentence.getRemainingNumber());
      if (sentence.getRemainingNumber() > 0) {
        document.getElementById("statistics-filter-" + span).innerHTML += "<button onclick=showSentence()>Restam mais <b>" + sentence.getRemainingNumber() + "</b> frases. Clique aqui para carregar mais!</button><br><br>";
      }
    };
    showSentence();

    /*
    sentence = sentenceBuild(filtered);
    document.getElementById("statistics-filter-" + span).innerHTML = "<h1>Total de frases: " + sentence.length + "</h1>";
    for (let i = 0; i < sentence.length; i++){
      document.getElementById("statistics-filter-" + span).innerHTML += sentence[i];
    }*/
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
