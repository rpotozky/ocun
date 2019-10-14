//requires view.js
//requires stats.js
//requires display.js

function displayFunctional(resp){
  var functional = JSON.parse(resp);
  str = `<div class='source-list'><h1>Significados Funcionais em ${view.workspace[view.workspaceBufferIndex].language}:</h1><br><br>`;
  functional.forEach((el) => {
    str += `<p><button onclick="getAllomorph('${el.abbreviation}','${el.meaning}')">${el.meaning} <b>(${el.abbreviation})</b></button></p>`;
  });
  str += `</div>`;
  view.setWorkspaceContent(str);
  document.getElementById("load-status").innerHTML = "";
}

function displayLexical(resp){
  var functional = JSON.parse(resp);
  str = `<div class='source-list'><h1>Raízes em ${view.workspace[view.workspaceBufferIndex].language}:</h1><br><br>`;
  functional.forEach((el) => {
    str += `<p><button onclick="getAllomorph('${el.meaning}')">${el.meaning} <b>(${el.form})</b></button></p>`;
  });
  str += `</div>`;
  view.setWorkspaceContent(str);
  document.getElementById("load-status").innerHTML = "";
}

function displaySentence(resp){
  var functional = JSON.parse(resp);
  str = `<h1>Frases em ${view.workspace[view.workspaceBufferIndex].language}:</h1><br><br>`;
  str += sentenceBuild(functional);
  view.setWorkspaceContent(str);
  document.getElementById("load-status").innerHTML = "";
}

function displayAllomorph(resp){
  functional = JSON.parse(resp);
  str = `<h1>${view.workspace[view.workspaceBufferIndex].language} - Morfemas contendo o significado <em>${view.workspace[view.workspaceBufferIndex].query}</em>:</h1><br><br>`;
  functional.forEach((el) => {
    str += `<button class='button-morpheme' onclick="getMorphemeStatistics('${el.form}','${el.meaning}')"><p>${el.form}</p><p>${el.meaning}</p></button>`;
  })
  view.setWorkspaceContent(str);
  document.getElementById("load-status").innerHTML = "";
}


//Cria a lista de frases, fornecer JSON de frases.
function sentenceBuild(sentenceBase){
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
        //c = c < (color.length - 1) ? c + 1 : 0;
        sentenceTextOriginal += ' ';
        sentenceTextGloss += ' ';
        str += `<span style="margin-right: 10px;">&nbsp;</span>`;
      } else if (i != 0){
        sentenceTextOriginal += '-';
        sentenceTextGloss += '-';
      }
      sentenceTextOriginal += morpheme.form;
      sentenceTextGloss += morpheme.meaning;
      //str += `<button style="background-color: ${color[c]};" class='button-sentence' onclick="getMorphemeStatistics('${morpheme.form}','${morpheme.meaning}')"><p>${morpheme.form}</p><p>${morpheme.meaning}</p></button>`;
      str += `<button class='button-sentence' onclick="getMorphemeStatistics('${morpheme.form}','${morpheme.meaning}')"><p>${morpheme.form}</p><p>${morpheme.meaning}</p></button>`;
    });
    str += `<br><button class="button-sentence-translation"><p>"${sentence[0].translation}"</p></button>`;
    sentenceData = `<br><b> ${view.workspace[view.workspaceBufferIndex].language}: </b><br> ${sentenceTextOriginal} <br> ${sentenceTextGloss} <br> ${sentence[0].translation} <br>`;
    allSentenceData += sentenceData;
    str += `<button class="button-send-to-notes" onclick="view.addToNotes('${sentenceData}')"><p>Notas</p></button></div>`;
  });
  str += `<p><button onclick='view.addToNotes("${allSentenceData}")'>Enviar todas as frases para Notas</button>`;
  str += `</div>`;
  return str;
}

function wordBuild(wordBase){
  str = `<p><em>Total de palavras: ${wordBase.length}</em></p>`;
  wordBase.forEach((word) => {
    str += `<div class='sentence'>`;
    word.forEach((morpheme) => {
      str += `<button class='button-sentence' onclick="getMorphemeStatistics('${morpheme.form}','${morpheme.meaning}')"><p>${morpheme.form}</p><p>${morpheme.meaning}</p></button>`;
    });
    str += `</div>`;
  });
  return str;
}
