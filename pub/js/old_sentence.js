
var showSentence;

var SentenceBuild = function(sentenceBase) {
  //properties
  this.display = [];
  this.backgroundColor = [0,1]; //"#82b2b8", "#b88882"
  this.currentColor = 0;
  this.changeColor = false;
  this.wordId = 0;
  this.str = "";
  this.limit = 0;
  this.database = sentenceBase;
  this.datasize = sentenceBase.length;
  this.currentdisplay = 0;
  //methods
  this.getColor = function(){
    if(this.changeColor){
      if (this.currentColor == (this.backgroundColor.length - 1)) {
        this.currentColor = 0;
      }
      else {
        this.currentColor++;
      }
    }
    return this.currentColor;
  }
  this.generate = function(){
    if ((this.datasize - this.currentdisplay) < this.limit) {
      this.limit = this.datasize;
    }
    for(this.currentdisplay; this.currentdisplay < this.limit; this.currentdisplay++){
      for(j in this.database[this.currentdisplay]) {
        if(sentenceBase[this.currentdisplay][j].word_id == this.wordId){
          this.changeColor = false;
        }
        else {
          this.changeColor = true;
        }
        this.str += "<button id='sentence-button-color-" + this.backgroundColor[this.getColor(this.changeColor)] + "' " +
        "onclick='getAllomorph(" +
        this.database[this.currentdisplay][j].source_id +
        ", \"" +
        this.database[this.currentdisplay][j].meaning +
        "\")'>" +
        "<p>" + this.database[this.currentdisplay][j].form + "</p>" +
        "<p>" + this.database[this.currentdisplay][j].meaning + "</p>" +
        "</button></div>";
        this.wordId = this.database[this.currentdisplay][j].word_id;
        }
        this.str += "<br><button><p>" + this.database[this.currentdisplay][0].translation + "</p></button><br><br>"
        this.disp.push(this.str);
        this.str = "";
      }
    }
  this.next = function() {
    this.disp = [];
    this.limit += 25;
    this.generate();
  }
  this.getRemainingNumber = function(){
    return (this.datasize - this.currentdisplay);
  }
}
