<section id="query-menu">
  <button onclick="addWorkspace()">&plus; Adicionar área de trabalho </button>
</section>
<section>
  <div class="flex-container" id="query-workspace">
  </div>

</section>

<script type="text/javascript" src="js/ajax.js"></script>
<script type='text/javascript'>
// Important variables
var languageList = <?= json_encode($languageList) ?>;
var sourceList = <?= json_encode($sourceList) ?>;

// View and UI setup

var view = {
  objCount: 0,
  activeWorkspace: 0,
  workspaceBufferIndex: 0,
  notes: 'digite suas anotações aqui!',
  setWorkspaceContent: function(index, content) {
    this.workspace[index].content = content;
    document.getElementById("workspace-" + index + "-content").innerHTML = content;
  },
  setActiveWorkspace: function(index) {
    this.activeWorkspace = index;
  },
  setActiveWorkspaceContent: function(content) {
    this.workspace[this.activeWorkspace].content = content;
    document.getElementById("workspace-" + this.activeWorkspace + "-content").innerHTML = content;
  },
  getActiveWorkspaceContent: function() {
    return this.workspace[this.activeWorkspace].content;
  },
  addToNotes: function(str) {
    this.notes +=  str;
    document.querySelectorAll('[contenteditable]').forEach((el) => {
      el.innerHTML = this.notes;
    });
    this.saveNotes();
  },
  //Load notes when page loads.
  loadNotes: function() {
    em = '<?= $_SESSION['user']; ?>';
    Ajax("ajax.php?action=getUserNotes&email=" + em, display);
    function display(resp){
      if (JSON.parse(resp).notes != null) {
        view.notes = JSON.parse(resp).notes;
      }
    }
  }(),
  //Save notes to DB.
  saveNotes: function() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        console.log(JSON.parse(this.responseText));
      }
    };
    xhttp.open("POST", "ajax.php?action=setUserNotes", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("email=<?= $_SESSION['user']; ?>&notes=" + this.notes);
  },
  //Saves notes each minute
  setAutoSave: function() {
    setInterval(function() {
      view.saveNotes();
      console.log("saving notes...");
    }, 60000);
  }(),
  workspace: []
}

function remWorkspace() {
  index = view.activeWorkspace;
  var elem = document.querySelector('#workspace-' + index);
  elem.parentNode.removeChild(elem);
  //document.getElementById("workspace-0").innerHTML = "";
  delete view.workspace[index];
}

function expandWorkspace(){
  index = view.activeWorkspace;
  if (view.workspace[index].width < 100) {
    view.workspace[index].width += 10;
    document.getElementById("workspace-" + index).style["width"] = view.workspace[index].width + "%";
  }
}

function maximizeWorkspace(){
  index = view.activeWorkspace;
  view.workspace[index].width = 100;
  document.getElementById("workspace-" + index).style["width"] = view.workspace[index].width + "%";
}

function minimizeWorkspace(){
  index = view.activeWorkspace;
  view.workspace[index].width = 20;
  document.getElementById("workspace-" + index).style["width"] = view.workspace[index].width + "%";
}

function reduceWorkspace(){
  index = view.activeWorkspace;
  if (view.workspace[index].width > 20) {
    view.workspace[index].width -= 10;
    document.getElementById("workspace-" + index).style["width"] = view.workspace[index].width + "%";
  }
}

function addWorkspace() {
  var index = view.objCount;
  content = "<h1>Olá! Sou uma nova área de trabalho!</h1>" +
      "<p>Para começar a navegar pelos dados de òcun, clique em <button>Língua</button> no menu acima para escolher uma língua e uma fonte.</p>" +
      "<p>Para tomar notas, clique em <button>Notas</button> no menu." +
      "<p>Se precisar de mais ajuda, clique em <button>?</button>."
      "<h2>Divirta-se!</h2>";
  menu = "<div class='workspace' id='workspace-" + index +"' onmouseover='view.setActiveWorkspace(" + index + ")'>" +
      "<div class='workspace-menu'>" +
      "<button onclick='showLanguageInterface()'>Língua</button>" +
      "<button onclick='showNotesInterface()'>Notas</button> " +
      "<button onclick='minimizeWorkspace()'>&larr;</button> " +
      "<button onclick='reduceWorkspace()'>&minus;</button> " +
      "<button onclick='expandWorkspace()'>&plus;</button> " +
      "<button onclick='maximizeWorkspace()'>&rarr;</button> " +
      "<button onclick='showWorkspaceHelp()'>?</button> " +
      "<button onclick='remWorkspace()'>&times;</button>" +
      "</div>" +
      "<div id='workspace-" + index + "-content'></div>" +
      "</div>";
  view.workspace.push({index: index, html: menu, width: 40, content: '', back: ''});
  view.workspace[index].content = content;
  view.objCount++;
  document.getElementById("query-workspace").innerHTML += menu;
  document.getElementById(`workspace-${index}-content`).innerHTML = content;
}

//Display Content:

function showLanguageInterface(){
  str = "<h1>Selecione uma língua</h1>" +
  "<p>Abaixo estão relacionas as línguas que fazem parte de nosso banco de dados. Escolha uma delas para iniciar a navegação! ⎈";
  languageList.forEach((el) => {
    str += `<p><button onclick="showLanguageInfo('${el.code}','${el.name}')">${el.name}</button></p>`;
  });
  view.setActiveWorkspaceContent(str);
}

function showLanguageInfo(code, lname){
  sources = sourceList.filter((el) =>{
    return el.language_code == code;
  });
  languageList.forEach((el) => {
    if (el.code == code) {
      str = `<h1>${el.name}</h1><p><b>Região:</b> ${el.region}</p><p><b>Falantes:</b> ${el.speakers}</p>`;
      str += `<h2>Fontes: </h2>`;
      sources.forEach((el) => {
        str += `<div class='source-list'><p><b>Autoria:</b> ${el.author}</p>`;
        str += `<p><b>Título:</b> ${el.title}</p>`;
        str += `<p><em>Publicado em ${el.year} por ${el.publisher}</em></p>`;
        str += `<p> ${el.license} </p>`;
        str += `<button onclick="viewFunctional(${el.id},'${lname}','${code}')">Significados Funcionais</button>`;
        str += `<button onclick="viewLexical(${el.id},'${lname}','${code}')">Raízes</button>`;
        str += `<button onclick="viewSentence(${el.id},'${lname}','${code}')">Frases</button></div>`;
      })
    }
    view.setActiveWorkspaceContent(str);
  });
}

function viewFunctional(sourceId, languageName, code){
  view.workspaceBufferIndex = view.activeWorkspace;
  Ajax("ajax.php?action=getFunctional&id=" + sourceId, display);
  view.setActiveWorkspaceContent("Carregando...");
  function display(resp){
    var functional = JSON.parse(resp);
    str = `<div class='source-list'><button onclick="showLanguageInfo('${code}','${languageName}')">Voltar</button><h1>Significados Funcionais em ${languageName}:</h1><br><br>`;
    functional.forEach((el) => {
      str += `<p><button onclick="getAllomorph('${code}','${languageName}',${el.source_id},'${el.abbreviation}','${el.meaning}')">${el.meaning} <b>(${el.abbreviation})</b></button></p>`;
    });
    str += `</div>`;
    view.setWorkspaceContent(view.workspaceBufferIndex, str);
  }
}

function viewLexical(sourceId, languageName, code){
  view.workspaceBufferIndex = view.activeWorkspace;
  Ajax("ajax.php?action=getRoot&id=" + sourceId, display);
  view.setActiveWorkspaceContent("Carregando...");
  function display(resp){
    var functional = JSON.parse(resp);
    str = `<div class='source-list'><button onclick="showLanguageInfo('${code}','${languageName}')">Voltar</button><h1>Raízes em ${languageName}:</h1><br><br>`;
    functional.forEach((el) => {
      str += `<p><button onclick="getAllomorph('${code}','${languageName}',${el.source_id},'${el.meaning}')">${el.meaning} <b>(${el.form})</b></button></p>`;
    });
    str += `</div>`;
    view.setWorkspaceContent(view.workspaceBufferIndex, str);
  }
}

function viewSentence(sourceId, languageName, code){
  view.workspaceBufferIndex = view.activeWorkspace;
  Ajax("ajax.php?action=getSentence&id=" + sourceId, display);
  view.setActiveWorkspaceContent("Carregando...");
  function display(resp){
    var functional = JSON.parse(resp);
    str = `<div class='source-list'><button onclick=showLanguageInfo('${code}','${'languageName'}')>Voltar</button>`;
    str += `<h1>Frases em ${languageName}:</h1><br><br>`;
    str += sentenceBuild(functional, sourceId);
    view.setWorkspaceContent(view.workspaceBufferIndex, str);
  }
}

//Cria a lista de frases, fornecer JSON de frases.
function sentenceBuild(sentenceBase, sourceId){
  str = `<p><em>Total de frases: ${sentenceBase.length}</em></p>`;
  allSentenceData = '';
  sentenceBase.forEach((sentence) => {
    str += `<div class='sentence'>`;
    c = 0;
    sentenceTextOriginal = '';
    sentenceTextGloss = '';
    sentence.forEach((morpheme, i) => {
      color = ['#7eadba', '#ba8b7e'];
      if (i != 0 && sentence[i].word_id != sentence[i-1].word_id){
        c = c < (color.length - 1) ? c + 1 : 0;
        sentenceTextOriginal += ' ';
        sentenceTextGloss += ' ';
      } else if (i != 0){
        sentenceTextOriginal += '-';
        sentenceTextGloss += '-';
      }
      sentenceTextOriginal += morpheme.form;
      sentenceTextGloss += morpheme.meaning;
      str += `<button style="background-color: ${color[c]};" class='button-morpheme' onclick="morphemeStatistics(${sourceId},'${morpheme.form}','${morpheme.meaning}')"><p>${morpheme.form}</p><p>${morpheme.meaning}</p></button>`;
    });
    str += `<p><button><p>${sentence[0].translation}</p></button></p>`;
    sentenceData = `<br> ${sentenceTextOriginal} <br> ${sentenceTextGloss} <br> ${sentence[0].translation} <br>`;
    allSentenceData += sentenceData;
    str += `<p><button onclick="view.addToNotes('${sentenceData}')">Enviar para Notas</button></div>`;
  });
  str += `<p><button onclick='view.addToNotes("${allSentenceData}")'>Enviar todas as frases para Notas</button>`;
  str += `</div>`;
  return str;
}

//Cria a lista de morfemas com mesmo significado (alomorfes)
function getAllomorph(code, languageName, sourceId, meaning, explanation = null){
  view.workspaceBufferIndex = view.activeWorkspace;
  Ajax("ajax.php?action=getAllomorph&id=" + sourceId + "&meaning=" + meaning, display);
  view.setActiveWorkspaceContent("Carregando...");
  meaning = (explanation != null) ? explanation : meaning;
  function display(resp){
    functional = JSON.parse(resp);
    str = `<div class='source-list'><button onclick="showLanguageInfo('${code}','${languageName}')">Voltar</button>`;
    str += `<h1>${languageName} - Morfemas com o significado <em>${meaning}</em>:</h1><br><br>`;
    functional.forEach((el) => {
      str += `<button class='button-morpheme' onclick="morphemeStatistics(${sourceId},'${el.form}','${el.meaning}')"><p>${el.form}</p><p>${el.meaning}</p></button>`;
    })

    view.setWorkspaceContent(view.workspaceBufferIndex, str);
  }
}

function morphemeStatistics(sourceId, form, meaning){
  view.workspaceBufferIndex = view.activeWorkspace;
  Ajax("ajax.php?action=getSentence&id=" + sourceId, display);
  view.setActiveWorkspaceContent("Carregando...");
  function display(resp){
    var functional = JSON.parse(resp);
    str = `<h1>Morfema: <button class='button-morpheme'><p>/${form}/</p><p>${meaning}</p></button></h1><br><br>`;
  view.setWorkspaceContent(view.workspaceBufferIndex, str);
}
}

function showNotesInterface(){
  view.workspace[view.activeWorkspace].back = view.getActiveWorkspaceContent();
  view.setActiveWorkspaceContent(`<button onclick="view.setActiveWorkspaceContent(view.workspace[view.activeWorkspace].back)">Voltar</button>
  <button onclick="view.saveNotes()">Salvar</button>
  <div class='workspace-notes' contenteditable='true'>${view.notes}</div>`);

  var content = document.querySelector('[contenteditable]');

  content.addEventListener('input', function(event) {
  view.notes = content.innerHTML;

  });
}

function showWorkspaceHelp(){
  view.setActiveWorkspaceContent("....");
}


</script>
