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
