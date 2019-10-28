function Ajax(action, resp){
  document.getElementById("load-status").innerHTML = "buscando dados no servidor...";
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        resp(this.responseText);
    }
  };
  xmlhttp.open("GET", action, true);
  xmlhttp.send();
}

function displayAjaxDataInWorkspace(resp){
  view.setWorkspaceBufferIndex();
  document.getElementById("load-status").innerHTML = "Renderizando dados...";
  view.setWorkspaceContent(resp);
  document.getElementById("load-status").innerHTML = "";
}

/* DEPRECATED CODE

function ajaxQuery(sourceId, ajax){
  view.setWorkspaceBufferIndex();
  view.setWorkspaceSource(sourceId);
  Ajax(ajax[0], ajax[1]);
  document.getElementById("load-status").innerHTML = "carregando...";
}

function getAllomorph(meaning, explanation = null){
  view.setWorkspaceBufferIndex();
  Ajax("ajax.php?action=getAllomorph&id=" + view.workspace[view.workspaceBufferIndex].source + "&meaning=" + meaning, displayAllomorph);
  document.getElementById("load-status").innerHTML = "carregando...";
  view.setWorkspaceQuery((explanation != null) ? explanation : meaning);
}

function getMorphemeStatistics(form, meaning){
  view.setWorkspaceBufferIndex();
  Ajax("ajax.php?action=getSentenceAndWord&id=" + view.workspace[view.workspaceBufferIndex].source, displayMorphemeStatistics);
  document.getElementById("load-status").innerHTML = "carregando...";
  view.setWorkspaceQuery(JSON.stringify({form: form, meaning: meaning}));
} */
