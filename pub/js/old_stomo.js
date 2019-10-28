
const groupBy = key => array =>
  array.reduce((objectsByKeyValue, obj) => {
    const value = obj[key];
    objectsByKeyValue[value] = (objectsByKeyValue[value] || []).concat(obj);
    return objectsByKeyValue;
  }, {});



var stomo = {
  makeList: function(data){
    initialList = [];
    for(sentence in data){
      for(morpheme in data[sentence]){
        initialList.push(data[sentence][morpheme]);
      }
    }
    for(i in initialList){
      if(i == 0 || initialList[i-1].phrase_id != initialList[i].phrase_id){
        if (i != 0){
          stomo.enhancedList.push({
            phrase_id: initialList[i-1].phrase_id,
            form: '>',
            meaning: 'series ends'
          });
        }
        stomo.enhancedList.push({
          phrase_id: initialList[i].phrase_id,
          form: '<',
          meaning: 'series starts'
        });
      }
      stomo.list.push(initialList[i]);
      stomo.enhancedList.push(initialList[i]);
      if(i == initialList.length - 1){
        stomo.enhancedList.push({
          phrase_id: initialList[i].phrase_id,
          form: '>',
          meaning: 'series ends'
        });
      }

    }

  },
  analyseUnigramProbability: function(list){
    for(i in list){
      let countMorpheme = 0;
      for (j in list){
        if (list[i].form == list[j].form && list[i].meaning == list[j].meaning){
          countMorpheme++;
        }
      }
      list[i].count = countMorpheme;
      list[i].unigramP = countMorpheme / list.length;
      list[i].unigramLogP = -1 * Math.log2(list[i].unigramP);
    }
  },
  analyseMeaningUnigramProbability: function(list){
    for(i in list){
      let countMorpheme = 0;
      for (j in list){
        if (list[i].meaning == list[j].meaning){
          countMorpheme++;
        }
      }
      list[i].count = countMorpheme;
      list[i].meaningUnigramP = countMorpheme / list.length;
      list[i].meaningUnigramLogP = -1 * Math.log2(list[i].meaningUnigramP);
    }
  },
  analyseRootUnigramProbability: function(list, listRoot){
    roots = JSON.parse(listRoot);
    //make root fields
    for(i in list){
      if (roots.find(el => {
        return el.id == list[i].morpheme_id;
      }) != undefined){
        list[i].rmeaning = 'root';
      }
      else{
        list[i].rmeaning = list[i].meaning;
      }
    }
    //count probabilities
    for(i in list){
      let countMorpheme = 0;
      for (j in list){
        if (list[i].rmeaning == list[j].rmeaning){
          countMorpheme++;
        }
      }
      list[i].rcount = countMorpheme;
      list[i].rmeaningUnigramP = countMorpheme / list.length;
      list[i].rmeaningUnigramLogP = -1 * Math.log2(list[i].rmeaningUnigramP);
    }
  },
  analyseBigramProbability: function(list){
    for(i = 1; i < list.length; i++){
      let countBigram = 0;
      for(j = 1; j < list.length; j++){
        if((list[i].form == list[j].form && list[i].meaning == list[j].meaning) &&
        (list[i-1].form == list[j-1].form && list[i-1].meaning == list[j-1].meaning)){
          countBigram++;
        }
      }
      bigramProbability = countBigram / list.length;
      if (list[i].form != "<"){
        list[i].bigramPgivenA = bigramProbability/list[i-1].unigramP;
        list[i].bigramLogPgivenA = (-1 * Math.log2(bigramProbability)) - list[i-1].unigramLogP;
      }
    }
  },
  analyseMeaningBigramProbability: function(list){
    for(i = 1; i < list.length; i++){
      let countBigram = 0;
      for(j = 1; j < list.length; j++){
        if(list[i].meaning == list[j].meaning && list[i-1].meaning == list[j-1].meaning){
          countBigram++;
        }
      }
      bigramProbability = countBigram / list.length;
      if (list[i].form != "<"){
        list[i].meaningBigramPgivenA = bigramProbability/list[i-1].meaningUnigramP;
        list[i].meaningBigramLogPgivenA = (-1 * Math.log2(bigramProbability)) - list[i-1].meaningUnigramLogP;
      }
    }
  },
  analyseRootBigramProbability: function(list){
    for(i = 1; i < list.length; i++){
      let countBigram = 0;
      for(j = 1; j < list.length; j++){
        if(list[i].rmeaning == list[j].rmeaning && list[i-1].rmeaning == list[j-1].rmeaning){
          countBigram++;
        }
      }
      bigramProbability = countBigram / list.length;
      if (list[i].form != "<"){
        list[i].rmeaningBigramPgivenA = bigramProbability/list[i-1].rmeaningUnigramP;
        list[i].rmeaningBigramLogPgivenA = (-1 * Math.log2(bigramProbability)) - list[i-1].rmeaningUnigramLogP;
      }
    }
  },
  exportEnhanced: function(resp) {
      let dataStr = JSON.stringify(stomo.enhancedList);
      let dataUri = URL.createObjectURL(new Blob([dataStr],{type:"text/plain"}));
      let exportFileDefaultName = 'enhanced.json';
      let linkElement = document.createElement('a');
      linkElement.setAttribute('href', dataUri);
      linkElement.setAttribute('download', exportFileDefaultName);
      linkElement.click();
  },
  makeAnalyses: function(resp){
    stomo.makeList(stomo.data);
    stomo.analyseUnigramProbability(stomo.list);
    stomo.analyseUnigramProbability(stomo.enhancedList);
    stomo.analyseBigramProbability(stomo.enhancedList);
    stomo.analyseMeaningUnigramProbability(stomo.enhancedList);
    stomo.analyseMeaningBigramProbability(stomo.enhancedList);
    stomo.analyseRootUnigramProbability(stomo.enhancedList, resp);
    stomo.analyseRootBigramProbability(stomo.enhancedList);
    stomo.exportEnhanced();
  },
  setData: function(resp){
    stomo.source = view.workspace[view.activeWorkspace].source;
    stomo.data = resp;
    stomo.list = [];
    stomo.enhancedList = [];
    Ajax(`ajax.php?action=getRoot&id=${stomo.source}`, stomo.makeAnalyses);
  },
  receive: function(resp){
    stomo.setData(JSON.parse(resp));
  },

}
