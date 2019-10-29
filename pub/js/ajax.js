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

function getJSON(resp){
  document.getElementById("load-status").innerHTML = "Renderizando dados...";
  console.log(JSON.parse(resp));
  let dataStr = JSON.stringify(JSON.parse(resp));
  let dataUri = URL.createObjectURL(new Blob([dataStr],{type:"text/plain"}));
  let exportFileDefaultName = 'sentences.json';
  let linkElement = document.createElement('a');
  linkElement.setAttribute('href', dataUri);
  linkElement.setAttribute('download', exportFileDefaultName);
  linkElement.click();
  document.getElementById("load-status").innerHTML = "";
}
