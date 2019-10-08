//requires view.js
//requires stats.js
//requires display.js

var dataStream = {
}

function pLog(probability){
  return -1 * Math.log2(probability);
}

function entropy(probabilities){
  z = 0
  for (p in probabilities){
    z += probabilities[p] * Math.log2(probabilities[p]);
  }
  return -1 * z;
}

function languageEntropy(resp){
  var functional = JSON.parse(resp);
  var morphemeList = {};
  var bigramList = {};
  var countMorphemes = 0;
  var countBigrams = 0;
  var unigramH = 0;
  var bigramH = 0;
  var sumP = 0;
  functional.forEach((sentence) => {
    sentence.forEach((morpheme, i) => {
      countMorphemes++;
      if (typeof(morphemeList[morpheme.morpheme_id]) == 'undefined'){
        morphemeList[morpheme.morpheme_id] = {
          form: morpheme.form,
          meaning: morpheme.meaning,
          count: 1};
      } else {
        morphemeList[morpheme.morpheme_id].count++;
      }
      if (i > 0){
        countBigrams++;
        if(typeof(bigramList[sentence[i-1].morpheme_id+'-'+morpheme.morpheme_id]) == 'undefined'){
          bigramList[sentence[i-1].morpheme_id+'-'+morpheme.morpheme_id] = {
            form_0: sentence[i-1].form,
            form_1: morpheme.form,
            meaning_0: sentence[i-1].meaning,
            meaning_1: morpheme.meaning,
            count: 1};
          } else {
            bigramList[sentence[i-1].morpheme_id+'-'+morpheme.morpheme_id].count++;
          }
        }
      })
    })
  for (i in morphemeList){
    morphemeList[i].probability = morphemeList[i].count / countMorphemes;
    morphemeList[i].pLog = pLog(morphemeList[i].probability);
    unigramH += morphemeList[i].probability * Math.log2(morphemeList[i].probability);
    sumP += morphemeList[i].probability
  }
  for (i in bigramList){
    bigramList[i].probability = bigramList[i].count / countMorphemes;
    bigramList[i].pLog = pLog(bigramList[i].probability);
    for(j in morphemeList){
      if (morphemeList[j].form == bigramList[i].form_0 && morphemeList[j].meaning == bigramList[i].meaning_0){
        bigramList[i].pA = morphemeList[j].probability;
        bigramList[i].pLogA = morphemeList[j].pLog;
        bigramList[i].pB_given_A = bigramList[i].probability / bigramList[i].pA;
        bigramList[i].pLogB_given_A = bigramList[i].pLog - bigramList[i].pLogA;
      }
    }
    bigramH += bigramList[i].pB_given_A * Math.log2(bigramList[i].pB_given_A);
  }
  console.log(morphemeList);
  console.log(bigramList);
  bigramH = -1 * sumP * bigramH;
  unigramH = -1 * unigramH;
  ul = [];
  for (i in morphemeList){
    ul.push(morphemeList[i]);
  }
  dataStream.unigramList = ul;
  bl = [];
  for (i in bigramList){
    bl.push(bigramList[i]);
  }
  dataStream.bigramList = bl;
  document.getElementById("lang-entropy-" + view.activeWorkspace).innerHTML = `
  <h2>Entropia Morfológica</h2>
  <p><b>Entropia 0-ordem:</b> ${unigramH} <button onclick="exportToCsvFile(dataStream.unigramList)">Baixar dados</button></p>
  <p><b>Entropia 1-ordem:</b> ${bigramH} <button onclick="exportToCsvFile(dataStream.bigramList)">Baixar dados</button></p>`;
}

function parseJSONToCSVStr(jsonData) {
    if(jsonData.length == 0) {
        return '';
    }

    let keys = Object.keys(jsonData[0]);

    let columnDelimiter = ',';
    let lineDelimiter = '\n';

    let csvColumnHeader = keys.join(columnDelimiter);
    let csvStr = csvColumnHeader + lineDelimiter;

    jsonData.forEach(item => {
        keys.forEach((key, index) => {
            if( (index > 0) && (index < keys.length-1) ) {
                csvStr += columnDelimiter;
            }
            csvStr += item[key];
        });
        csvStr += lineDelimiter;
    });

    return encodeURIComponent(csvStr);;
}

function exportToCsvFile(jsonData) {
    let csvStr = parseJSONToCSVStr(jsonData);
    let dataUri = 'data:text/csv;charset=utf-8,'+ csvStr;

    let exportFileDefaultName = 'data.csv';

    let linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
}

function exportToJsonFile(jsonData) {
    let dataStr = JSON.stringify(jsonData);
    let dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);

    let exportFileDefaultName = 'data.json';

    let linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
}


function displayMorphemeStatistics(resp){
  var query = JSON.parse(view.workspace[view.workspaceBufferIndex].query);
  var form = query.form;
  var meaning = query.meaning;
  var languageName = view.workspace[view.workspaceBufferIndex].language;
  var functional = JSON.parse(resp);
  var stats = {
    totalMorphemes: 0,
    totalFormMeaning: 0,
    totalForm: 0,
    totalMeaning: 0,
    morphemeProbabilityAmongMorphemes: function(){
      return stats.totalFormMeaning / stats.totalMorphemes;
    },
    morphemeProbabilityAmongAllomorphs: function(){
      return stats.totalFormMeaning / (stats.totalMeaning + stats.totalFormMeaning);
    },
    morphemeProbabilityAmongHomonyms: function(){
      return stats.totalFormMeaning / (stats.totalForm + stats.totalFormMeaning);
    },
    meaningEntropy: function(){
      probabilities = [this.morphemeProbabilityAmongAllomorphs()];
      totalMeaning = this.totalFormMeaning + this.totalMeaning;
      for (i in this.allomorphs){
        probabilities.push(this.allomorphs[i].count/totalMeaning);
      }
      return entropy(probabilities);
    },
    formEntropy: function(){
      probabilities = [this.morphemeProbabilityAmongAllomorphs()];
      totalMeaning = this.totalFormMeaning + this.totalForm;
      for (i in this.homonyms){
        probabilities.push(this.homonyms[i].count/totalMeaning);
      }
      return entropy(probabilities);
    },
    allomorphs: {},
    homonyms: {},
    wordsWithMorpheme: [],
    wordsWithHomonym: [],
    wordsWithAllomorph: [],
    sentencesWithMorpheme: [],
    sentencesWithHomonyms: [],
    sentencesWithAllomorphs: [],
  }

  var feedStats = function(){
    functional.sentences.forEach((sentence) => {
      sentence.forEach((morpheme, i) => {
        stats.totalMorphemes++;
        if (morpheme.form == form && morpheme.meaning == meaning){
          stats.totalFormMeaning++;
          stats.sentencesWithMorpheme.push(sentence);
        } else if (morpheme.form == form){
          stats.totalForm++;
          stats.sentencesWithHomonyms.push(sentence);
          //create homonyms object
          if (typeof(stats.homonyms[morpheme.morpheme_id]) == 'undefined'){
            stats.homonyms[morpheme.morpheme_id] = {form: morpheme.form, meaning: morpheme.meaning, count: 1};
          } else {
            stats.homonyms[morpheme.morpheme_id].count++;
          }
        } else if (morpheme.meaning == meaning) {
          stats.totalMeaning++;
          stats.sentencesWithAllomorphs.push(sentence);
          //create allomorphs object
          if (typeof(stats.allomorphs[morpheme.morpheme_id]) == 'undefined'){
            stats.allomorphs[morpheme.morpheme_id] = {form: morpheme.form, meaning: morpheme.meaning, count: 1};
          } else {
            stats.allomorphs[morpheme.morpheme_id].count++;
          }
        }
      })
    })
    functional.words.forEach((word) => {
      word.forEach((morpheme) => {
        if (morpheme.form == form && morpheme.meaning == meaning){
          stats.wordsWithMorpheme.push(word);
        } else if (morpheme.form == form){
          stats.wordsWithHomonym.push(word);
        } else if (morpheme.meaning == meaning) {
          stats.wordsWithAllomorph.push(word);
        }
      })
    })
  }()

  str = `<h1>${languageName}</h1>
      <h1><button class='button-morpheme'><p>${form}</p><p>${meaning}</p></button></h1><br><br>`;
  str += `<p><b>Ocorrências: </b>${stats.totalFormMeaning} em ${stats.totalMorphemes}
  <br><b>P:</b> ${stats.morphemeProbabilityAmongMorphemes()}
  <br><b>-logP:</b> ${pLog(stats.morphemeProbabilityAmongMorphemes())}`;
    //Alomorfes:
  str += `<h2>Alomorfes:</h2>
      <p><em>Entropia do significado = ${stats.meaningEntropy()}</em></p>`
  str += `<h1><button style="border-width: medium;"><div><p>${form}</p><p>${meaning}</p><p>${(stats.morphemeProbabilityAmongAllomorphs() * 100).toFixed(2) + "%"}</p></div></button>`;
  for(i in stats.allomorphs){
    str += `<button onclick="getMorphemeStatistics('${stats.allomorphs[i].form}','${stats.allomorphs[i].meaning}')"><div><p>${stats.allomorphs[i].form}</p>
    <p>${stats.allomorphs[i].meaning}</p><p>${((stats.allomorphs[i].count/(stats.totalMeaning + stats.totalFormMeaning)) * 100).toFixed(2) + "%"}</p></div></button>`;
  }
  str += `</h1><br><br>`;
  //Homônimos:
  str += `<h2>Homônimos:</h2>
    <p><em>Entropia da forma = ${stats.formEntropy()}</em></p>`
  str += `<h1><button style="border-width: medium;"><div><p>${form}</p><p>${meaning}</p><p>${(stats.morphemeProbabilityAmongHomonyms() * 100).toFixed(2) + "%"}</p></div></button>`;
  for(i in stats.homonyms){
    str += `<button onclick="getMorphemeStatistics('${stats.homonyms[i].form}','${stats.homonyms[i].meaning}')"><div><p>${stats.homonyms[i].form}</p>
    <p>${stats.homonyms[i].meaning}</p><p>${((stats.homonyms[i].count/(stats.totalForm + stats.totalFormMeaning)) * 100).toFixed(2) + "%"}</p></div></button>`;
  }
  str += `</h1><br><br>`;
  str += `<h2>Frases com o morfema:</h2>`;
  str += sentenceBuild(stats.sentencesWithMorpheme);
  str += `<h2 id="words-with-morpheme">Palavras com o morfema:</h2>`;
  str += wordBuild(stats.wordsWithMorpheme);
  console.log(stats.wordsWithMorpheme);

  view.setWorkspaceContent(str);
  document.getElementById("load-status").innerHTML = "";
}
