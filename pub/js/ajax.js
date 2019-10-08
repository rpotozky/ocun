//requires view.js
//requires stats.js
//requires display.js

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

function ajaxQuery(sourceId, ajax){
  view.setWorkspaceBufferIndex();
  view.setWorkspaceSource(sourceId);
  Ajax(ajax[0], ajax[1]);
  document.getElementById("load-status").innerHTML = "carregando..."
}

function getAllomorph(meaning, explanation = null){
  view.setWorkspaceBufferIndex();
  Ajax("ajax.php?action=getAllomorph&id=" + view.workspace[view.workspaceBufferIndex].source + "&meaning=" + meaning, displayAllomorph);
  document.getElementById("load-status").innerHTML = "carregando..."
  view.setWorkspaceQuery((explanation != null) ? explanation : meaning);
}

function getMorphemeStatistics(form, meaning){
  view.setWorkspaceBufferIndex();
  Ajax("ajax.php?action=getSentenceAndWord&id=" + view.workspace[view.workspaceBufferIndex].source, displayMorphemeStatistics);
  document.getElementById("load-status").innerHTML = "carregando..."
  view.setWorkspaceQuery(JSON.stringify({form: form, meaning: meaning}));
}
