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
