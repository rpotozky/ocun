// requires ajax.js

var view = {
  // Important variables for navigating through the windows.
  objCount: 0,
  activeWorkspace: 0,
  workspaceBufferIndex: 0,
  workspace: [],
  notes: 'digite suas anotações aqui!',


  // Workspace Template with Menu:
  workspaceTemplate: function() {
      return `
      <div class='workspace' id='workspace-${this.objCount}' onmouseover='view.setActiveWorkspace(${this.objCount})'>
      <div class='workspace-menu'>
      <button onclick='Ajax("ajax.php?action=languageList", displayAjaxDataInWorkspace)'>Língua</button>
      <button onclick='view.showNotesInterface()'>Notas</button>
      <button onclick='view.back()'>&larr;</button>
      <button onclick='view.resize(31)'>30%</button>
      <button onclick='view.resize(48)'>50%</button>
      <button onclick='view.resize(65)'>70%</button>
      <button onclick='view.resize(100)'>100%</button>
      <button onclick='view.duplicateWorkspace()'>Duplicar</button>
      <button onclick='view.showWorkspaceHelp()'>?</button>
      <button onclick='view.closeWorkspace()'>&times;</button>
      </div>
      <div id='workspace-${this.objCount}-content' class='workspace-content'></div>
      </div>`;},


  // Default Workspace Data:
  defaultWorkspace: function() {
    return {
      index: this.objCount,
      width: 100,
      content: `
        <h1>Olá! Sou uma nova área de trabalho!</h1>
        <p>Para começar a navegar pelos dados de òcun, clique em <button onclick='Ajax("ajax.php?action=languageList", displayAjaxDataInWorkspace)'>Língua</button>
 no menu acima para escolher uma língua e uma fonte.</p>
        <p>Para tomar notas, clique em <button onclick='view.showNotesInterface()'>Notas</button> no menu</p>
        <p>Se precisar de mais ajuda, clique em <button onclick='view.showWorkspaceHelp()'>?</button></p>
        <h2>Divirta-se!</h2>`,
      back: '',
      language: '',
      query: '',
      source: 0
    };
  },


  //Create a new Workspace:
  addWorkspace: function(workspace = "default") {
    //Set status bar to loading
    document.getElementById(`load-status`).innerHTML = "carregando...";

    if (workspace == "default") {
      this.workspace.push(this.defaultWorkspace());
    } else {
      newWorkspace = JSON.parse(workspace);
      newWorkspace.index = this.objCount;
      this.workspace.push(newWorkspace);
    }

    //Render initial content
    document.getElementById(`query-workspace`).innerHTML += this.workspaceTemplate();
    document.getElementById(`workspace-${this.objCount}-content`).innerHTML = this.workspace[this.objCount].content;

    //Increment object count
    this.objCount++;

    //Clear status bar
    document.getElementById("load-status").innerHTML = "";
  },


  //Close a Workspace
  closeWorkspace: function() {
    var elem = document.querySelector('#workspace-' + this.activeWorkspace);
    elem.parentNode.removeChild(elem);
    delete view.workspace[this.activeWorkspace];
  },

  //Duplicate a Workspace
  duplicateWorkspace: function(){
    document.getElementById("load-status").innerHTML = "carregando...";
    this.addWorkspace(JSON.stringify(this.workspace[this.activeWorkspace]));
    document.getElementById("load-status").innerHTML = "";
  },



  setWorkspaceBufferIndex: function(){
    this.workspaceBufferIndex = this.activeWorkspace;
  },
  setWorkspaceContent: function(content) {
    view.workspace[this.workspaceBufferIndex].back = this.getActiveWorkspaceContent();
    this.workspace[this.workspaceBufferIndex].content = content;
    document.getElementById("workspace-" + this.workspaceBufferIndex + "-content").innerHTML = content;
    document.getElementById("workspace-" + this.workspaceBufferIndex).scrollTop = 0;
  },
  setWorkspaceLanguage: function(language){
    view.workspace[this.activeWorkspace].language = language;
  },
  setWorkspaceSource: function(source){
    view.workspace[this.activeWorkspace].source = source;
  },
  setWorkspaceQuery: function(query){
    view.workspace[this.activeWorkspace].query = query;
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
  back: function() {
    document.getElementById("load-status").innerHTML = "carregando...";
    this.setActiveWorkspaceContent(this.workspace[this.activeWorkspace].back);
    document.getElementById("load-status").innerHTML = "";
  },
  resize: function(width) {
    this.workspace[this.activeWorkspace].width = width;
    document.getElementById("workspace-" + this.activeWorkspace).style["width"] = view.workspace[this.activeWorkspace].width + "%";
  },
  showNotesInterface: function(){
    view.workspace[view.activeWorkspace].back = view.getActiveWorkspaceContent();
    view.setActiveWorkspaceContent(`<button onclick="view.setActiveWorkspaceContent(view.workspace[view.activeWorkspace].back)">Voltar</button>
    <button onclick="view.saveNotes()">Salvar</button>
    <pre class='workspace-notes' contenteditable='true'>${view.notes}</pre>`);

    var content = document.querySelector('[contenteditable]');

    content.addEventListener('input', function(event) {
    view.notes = content.innerHTML;

    });
  },
  addToNotes: function(str) {
    this.notes +=  str;
    document.querySelectorAll('[contenteditable]').forEach((el) => {
      el.textContent = this.notes;
    });
    this.saveNotes();
  },
  loadNotes: function() {
    Ajax("ajax.php?action=getUserNotes", display);
    function display(resp, k){
      document.getElementById("load-status").innerHTML = "carregando notas...";
      console.log();
      if (JSON.parse(resp).notes != null) {
        view.notes = JSON.parse(resp).notes;
      }
      document.getElementById("load-status").innerHTML = "";
    }
  }(),
  saveNotes: function() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        console.log(this.responseText);
      }
    };
    xhttp.open("POST", "ajax.php?action=setUserNotes", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("notes=" + this.notes);
  },
  setAutoSave: function() {
    setInterval(function() {
      view.saveNotes();
      console.log("saving notes...");
    }, 60000);
  }(),
  showWorkspaceHelp: function(){
    view.setActiveWorkspaceContent("....");
  }
}
