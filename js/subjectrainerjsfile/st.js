
  function winOpen(URL){
      window.open(URL,'targetWindow', `toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=400`)
  }

  function windowReload(){
      document.location.href = document.location.href;
  }


function EscapeForJSON(inputStr){
  let retStr = inputStr;
  retStr = retStr.replace(/(\r)/gm,"\\r");
  retStr = retStr.replace(/(\n)/gm,"\n");
  retStr = retStr.replace(/(")/gm,'\\"');
  retStr = retStr.replace(/(\\)/gm,'\\\\');
  retStr = retStr.replace(/(\f)/gm,'\\f');
  retStr = retStr.replace(/(\b)/gm,'\\b');
  retStr = retStr.replace(/(\t)/gm,'\\t');

  return retStr;

}
function IsValidJSONStringV2(str, codeLocation) {
  try {
      JSON.parse(str);
  } catch (e) {
      alert(" Invalid JSON at location : " + codeLocation + "  \n\n" + str);
      return false;
  }
  return true;
}

function IsValidJSONString(str) {
  return IsValidJSONStringV2(str, " unknown location (hint: Use IsValidJSONStringV2 to specify location) ");
}

function CheckForFatalPHPError(str) {
  if(str.indexOf("PHP Fatal error") > -1 ){
    alert(" Fatal Error \n\n" + str);
  }
}

// Example POST method implementation:
       async function postDataGetJSON(url = '', data = {}) {
         //alert("data line 3 = " + data) ;
        // Default options are marked with *
        const response = await fetch(url, {
          method: 'POST', // *GET, POST, PUT, DELETE, etc.
          mode: 'cors', // no-cors, *cors, same-origin
          cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
          credentials: 'same-origin', // include, *same-origin, omit
          headers: {
            'Content-Type': 'application/json'
            // 'Content-Type': 'application/x-www-form-urlencoded',
          },
          redirect: 'follow', // manual, *follow, error
          referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
          body: JSON.stringify(data) // body data type must match "Content-Type" header
        });
        return response.json(); // parses JSON response into native JavaScript objects
      }
              
      // Example POST method implementation:
      async function postDataGetHTML(url = '', data = {}) {
        // Default options are marked with *
        const response = await fetch(url, {
          method: 'POST', // *GET, POST, PUT, DELETE, etc.
          mode: 'cors', // no-cors, *cors, same-origin
          cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
          credentials: 'same-origin', // include, *same-origin, omit
          headers: {
            'Content-Type': 'application/json'
            // 'Content-Type': 'application/x-www-form-urlencoded',
          },
          redirect: 'follow', // manual, *follow, error
          referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
          body: JSON.stringify(data) // body data type must match "Content-Type" header
        });
        return response.text(); // parses JSON response into native JavaScript objects
      }

      function FillDivJSON(divName, url, jsonPostData){  
        let divHavingServiceData = GetDivToLoadServicedata(divName);
        let resultObj = postDataGetJSON(url, jsonPostData)
        .then(data => {
            //console.log(data); // JSON data parsed by `data.json()` call
            //if ( !isNull(data.Exception)) {
            //  alert(data.Exception);
            //}
            document.getElementById(divHavingServiceData).innerText= JSON.stringify(data);

            return data;
        }).catch(function (err) {
              // There was an error
              console.warn('Something went wrong. (1) ' + err + " " +  document.getElementById(divName).innerText );
              //alert("Error line 49 " + err);
              document.getElementById(divHavingServiceData).innerText='st.js: line 98: Something went wrong. Check console.';

              resultObj = postDataGetHTML(url, jsonPostData)
            .then(data => {
                //console.log(data); // JSON data parsed by `data.json()` call
                document.getElementById(divHavingServiceData).innerText = data;
                CheckForFatalPHPError(data);
                console.warn('Something went wrong. (1a) ' + " " +  document.getElementById(divName).innerText);
                //alert(data);
                return data;
            }).catch(function (err) {
                // There was an error
                console.warn('Something went wrong. (2)' + err + " " + document.getElementById(divName).innerText);
                //alert("Error line 63 " + err);
                document.getElementById(divHavingServiceData).innerText='st.js: line 112: Something went wrong. Check console.';
            });   

        });
        return resultObj;       
    }
    
    function FillDivHTML(divName, url, jsonPostData){
        postDataGetHTML(url, jsonPostData)
        .then(data => {
            //console.log(data); // JSON data parsed by `data.json()` call
            document.getElementById(divName).innerHTML= data;
        }).catch(function (err) {
            // There was an error
            console.warn('Something went wrong. (3) ' + err + " " +  document.getElementById(divName).innerText);
            document.getElementById(divName).innerText='st.js: line 127: Something went wrong. Check console.';
        });
    }
    function DivInitSaveFormData(divName, payload){
      if(IsValidJSONString(document.getElementById(divName).innerText)){
        //Read the url and replace div content with JSON
        let divSourceObj =  JSON.parse(document.getElementById(divName).innerText);
        //alert(divSourceObj.payload.action);
        if (divSourceObj.payload.action == "SaveFormDataAndGetNewRowID") {
          return FillDivJSON(divName, divSourceObj.url, payload);       
        }
        if (divSourceObj.payload.action == "SaveFormDataAndUpdateRowGetRowID") {
          return FillDivJSON(divName, divSourceObj.url, payload);       
        }
        if (divSourceObj.payload.action == "DeleteFormDataAndRowGetRowID") {
          return FillDivJSON(divName, divSourceObj.url, payload);       
        }            
      }    
    }

    function GetHtmlRowTemplateOuter(divName){
      if(document.getElementById(divName).innerText.indexOf("DataFormatLevel02") > -1){
        if(document.getElementById(divName).innerText.indexOf("DataFormatLevel01") > -1){
          let divSourceObj =  JSON.parse(document.getElementById(divName).innerText);
          return document.getElementById(divSourceObj.DataFormatLevel01).innerHTML;  
        }
      }
      return "NO-TEMPLATE-AVAILABLE";
    }    

    function DivOutputAdditionalFormat(divOutputFormat, ParamDivContentStr, htmlRowTemplateOuter){
      let divContentStr = ParamDivContentStr
      if(divOutputFormat=="JSON_NUMBER_ARRAY"){
                
        divContentStr = RemoveLastComma(divContentStr);
        if(htmlRowTemplateOuter != "NO-TEMPLATE-AVAILABLE"){
          let val = divContentStr
          let lookFor = "{DataFormatLevel02}";
          htmlRowTemplateOuter = htmlRowTemplateOuter.replace(lookFor, val);
          divContentStr = htmlRowTemplateOuter;
        }                
      }
      return divContentStr;
    }
    
    //htmlRowTemplate
    function GetHtmlRowTemplate(divName){
      //if(GetDivOutputFormat(divName) == "DEFAULT"){

        //return "";
      //}
      if(document.getElementById(divName).innerText.indexOf("DataFormatLevel02") > -1){
        let divSourceObj =  JSON.parse(document.getElementById(divName).innerText);
        return document.getElementById(divSourceObj.DataFormatLevel02).innerHTML;  
      }
      if(document.getElementById(divName).innerText.indexOf("DataFormatLevel01") > -1){
        let divSourceObj =  JSON.parse(document.getElementById(divName).innerText);
        return document.getElementById(divSourceObj.DataFormatLevel01).innerHTML;  
      }      
      let DivDisplayTemplate = `${divName}Template1`;
      return document.getElementById(DivDisplayTemplate).innerHTML;
    }

    function GetDivOutputDisplay(divName){
      if(document.getElementById(divName).innerText.indexOf("DivForDisplayingResponseDisplay") > -1){
        let divSourceObj =  JSON.parse(document.getElementById(divName).innerText);
        return divSourceObj.DivForDisplayingResponseDisplay;  
      }
      return "block"; //valid return "block", "none"
    }

    function GetDivOutputFormat(divName){
      if(document.getElementById(divName).innerText.indexOf("responseViewFormatOption") > -1){
        let divSourceObj =  JSON.parse(document.getElementById(divName).innerText);
        return divSourceObj.responseViewFormatOption;  
      }
      return "DEFAULT"; //valid return "DEFAULT", "JSON_NUMBER_ARRAY"
    }

    function GetDivToLoadOutput(divName){
      if(document.getElementById(divName).innerText.indexOf("DivForDisplayingResponse") > -1){
        let divSourceObj =  JSON.parse(document.getElementById(divName).innerText);
        return divSourceObj.DivForDisplayingResponse;  
      }
      return divName; //if DivForDisplayingResponse is not specified then load in the currrent div
    }

    function GetDivToLoadServicedata(divName){
      if(document.getElementById(divName).innerText.indexOf("ServiceDataLocation") > -1){
        let divSourceObj =  JSON.parse(document.getElementById(divName).innerText);
        return divSourceObj.ServiceDataLocation;  
      }
      return divName; //if ServiceDataLocation is not specified then load in the currrent div
    }    

    function AlterPayload(divName){
      if(document.getElementById(divName).innerText.indexOf("alterpayload") > -1){
        let divSourceObj =  JSON.parse(document.getElementById(divName).innerText);
        let alterpayload = divSourceObj.alterpayload;  
        Object.keys(alterpayload).forEach((key) =>{ document.getElementById(key).value = alterpayload[key];});   
      }
    }

    function DivInitJSONWithPayload(divName){
      if(IsValidJSONString(document.getElementById(divName).innerText)){
        //Read the url and replace div content with JSON
        let divSourceObj =  JSON.parse(document.getElementById(divName).innerText);
        AlterPayload(divName);

        let frm = document.forms.namedItem(divSourceObj.payload.formID);
        let payload = $(frm).serializeFormJSON();

        //alert(JSON.stringify(payload));
        //alert(divSourceObj.payload.action);
        if (divSourceObj.payload.action == "ShowQuestion") {
          return FillDivJSON(divName, divSourceObj.url, payload);       
        }
        if (divSourceObj.payload.action == "QueryData") {
          return FillDivJSON(divName, divSourceObj.url, payload);       
        }   
        if (divSourceObj.payload.action == "ShowAllSubjects") {
          return FillDivJSON(divName, divSourceObj.url, payload);       
        }  
        if (divSourceObj.payload.action == "ShowPrevNext") {
          return FillDivJSON(divName, divSourceObj.url, payload);       
        }    
      }              
    }    

    function DivInitJSON(divName){
      if(IsValidJSONString(document.getElementById(divName).innerText)){
        //Read the url and replace div content with JSON
        let divSourceObj =  JSON.parse(document.getElementById(divName).innerText);
        return FillDivJSON(divName, divSourceObj.url, divSourceObj.payload);
      }
    }
    
    function DivInitHTML(divName){
      if(IsValidJSONString(document.getElementById(divName).innerText)){
        let divSourceObj =  JSON.parse(document.getElementById(divName).innerText);
        FillDivHTML(divName, divSourceObj.url, divSourceObj.payload);
      }
    }

    function RemoveLastComma(dataStr){
      let vdataStr = dataStr.trim();
      if( vdataStr.length>0 && vdataStr.lastIndexOf(",")> -1){
        if( (vdataStr.length-1)  == (vdataStr.lastIndexOf(",")) ){
            return vdataStr.substring(0, vdataStr.length-1);
        }
      }
      return dataStr;
    }

    function SetValueInForm(formelement, formvalue){
      document.getElementById(formelement).value = formvalue;
    }

    function DisplayDataInDivUsingJSONResponseFromDivAndFormData(divName, htmlRowTemplate){
      if(IsValidJSONString(document.getElementById(divName).innerText)){
        let divHavingServiceData = GetDivToLoadServicedata(divName);
        let divOutput = GetDivToLoadOutput(divName);
        let divOutputFormat =  GetDivOutputFormat(divName);
        let divOutputDisplay = GetDivOutputDisplay(divName);
        let htmlRowTemplateOuter =  GetHtmlRowTemplateOuter(divName);
        const resultObj = DivInitJSONWithPayload(divName).then(()=>{
          if(IsValidJSONString(document.getElementById(divHavingServiceData).innerText)){
            //alert(document.getElementById(divName).innerText);
            let jsonData = JSON.parse(document.getElementById(divHavingServiceData).innerText);         
            let divContentStr ="";
              for (let i = 0; i < jsonData.length; i++) { 
                let divContentRowStr = RemoveDoubleSquareBrackets(htmlRowTemplate);
                let row = jsonData[i];
                var keys = Object.keys(row);
                let styleStr="";
                keys.forEach(key => {
                  let lookFor = `{${key}}`;
                  let val = row[key];

                  let whileLoopCount = 0; 
                  let maxReplacement = 10; 
                  while (IsPresentInString(divContentRowStr, lookFor) && whileLoopCount < maxReplacement ) {
                    divContentRowStr = divContentRowStr.replace(lookFor, val);         
                    whileLoopCount = whileLoopCount + 1;   
                  }

                  //if(IsQueryStringValuePresentInURL(key, val)){
                    //styleStr = "style='background-color: lightblue;'"
                  //}           
                });
                //console.log(divContentRowStr);

                //divContentStr = `${divContentStr} <div ${styleStr}>${divContentRowStr}</div> <br>`;  
                divContentStr = `${divContentStr} ${divContentRowStr}`;  
              }                 

              divContentStr = DivOutputAdditionalFormat(divOutputFormat, divContentStr, htmlRowTemplateOuter); //e.g.: "JSON_NUMBER_ARRAY"

              document.getElementById(divOutput).innerHTML = divContentStr;   
              document.getElementById(divOutput).style["display"]=divOutputDisplay;    
          }
        });
        return resultObj;
     }
    }

    function isElementPresent(elementID){
      var element = document.getElementById(elementID);
 
      //If it isn't "undefined" and it isn't "null", then it exists.
      if(typeof(element) != 'undefined' && element != null){
          return true;
      } else{
        return false;
      }
    }

    function DisplayDataInFrom(divName){
      if(IsValidJSONString(document.getElementById(divName).innerText)){
        let jsonDataOrg = JSON.parse(document.getElementById(divName).innerText); 
        const resultObj = DivInitJSONWithPayload(divName).then(()=>{
          if(IsValidJSONString(document.getElementById(divName).innerText)){
            let jsonData = JSON.parse(document.getElementById(divName).innerText);         
            let divContentStr ="";
            for (let i = 0; i < jsonData.length; i++) { 
              let row = jsonData[i];
              var keys = Object.keys(row);
              let styleStr="";
              keys.forEach(key => {
                  let lookFor = `{${key}}`;
                  let val = row[key];
                  if(jsonDataOrg.formelements[key] == "hidden"){
                    document.getElementById(key).value = val;
                  }
                  if(jsonDataOrg.formelements[key] == "textarea"){
                    document.getElementById(key).value = val;
                  }   
                  if(jsonDataOrg.formelements[key] == "text"){
                    document.getElementById(key).value = val;
                  } 
                  if(jsonDataOrg.formelements[key] == "select"){
                    //alert("key " + key + " val " + val);
                    document.getElementById(key).value = val;
                  } 
                  if(isElementPresent(key+"CurrentData")){
                    document.getElementById(key+"CurrentData").innerHTML = val;
                  }                                                
              });
            } 
            //document.getElementById(divName).innerHTML = divContentStr;
          }
        });
      }
    }

    function TransformDivJSON2HTML(divName, htmlRowTemplate){
      const resultObj = DivInitJSON(divName).then(()=>{
        if(IsValidJSONString(document.getElementById(divName).innerText)){
          let jsonData = JSON.parse(document.getElementById(divName).innerText);
          
          let divContentStr ="";
          for (let i = 0; i < jsonData.length; i++) { 
            let divContentRowStr = RemoveDoubleSquareBrackets(htmlRowTemplate);
            let row = jsonData[i];
            var keys = Object.keys(row);
            let styleStr="";
            keys.forEach(key => {
              let lookFor = `{${key}}`;
              let val = row[key];

              let whileLoopCount = 0; 
              let maxReplacement = 10; 
              while (IsPresentInString(divContentRowStr, lookFor) && whileLoopCount < maxReplacement ) {
                divContentRowStr = divContentRowStr.replace(lookFor, val);    
                whileLoopCount = whileLoopCount + 1;           
              }

              if(IsQueryStringValuePresentInURL(key, val)){
                styleStr = "style='background-color: lightblue;'"
              }           
            });
            //console.log(divContentRowStr);

            divContentStr = `${divContentStr} <div ${styleStr}>${divContentRowStr}</div> <br>`;  
          } 
          document.getElementById(divName).innerHTML = divContentStr;   
        }
      });
  }

  function RemoveDoubleSquareBrackets(source){
    let newReturnString =source;
    let lookFor ="[[";

    let whileLoopCount = 0; 
    let maxReplacement = 10; 
    while (IsPresentInString(newReturnString, lookFor) && whileLoopCount < maxReplacement) {
      newReturnString = newReturnString.replace(lookFor, "");  
      whileLoopCount = whileLoopCount + 1;              
    }

    lookFor ="]]";

    whileLoopCount = 0; 
    maxReplacement = 10; 
    while (IsPresentInString(newReturnString, lookFor) && whileLoopCount < maxReplacement) {
      newReturnString = newReturnString.replace(lookFor, ""); 
      whileLoopCount = whileLoopCount + 1;              
    }

    return newReturnString;
  }
  function IsPresentInString(source, key){
    if (source.indexOf(key) > -1 ) {
      return true;
    }
    return false;
  }
  function IsQueryStringValuePresentInURL(qsKey, qsValue){
    //Ref: https://developer.mozilla.org/en-US/docs/Web/API/URLSearchParams
    let realQSvalue = GetQuerystringValue(qsKey);
    //console.log(`realQSvalue ${realQSvalue} looking for key i${qsKey} having value ${qsValue}`)  ; 
    if (realQSvalue == qsValue) {
      return true;
    }
    return false;
  }
  function GetQuerystringValue(qsKey){
    //console.log("qsKey " + qsKey);
    //console.log("document.location.search " + document.location.search);
    let realQSvalue =new URLSearchParams(document.location.search).get(`i${qsKey}`);
    //console.log("realQSvalue " + realQSvalue);
    return realQSvalue;
  }

  
  (function ($) {
    $.fn.serializeFormJSON = function () {

        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
})(jQuery);

function SetIDInHiddenField(hiddentElementID){
  document.getElementById(hiddentElementID).value = GetQuerystringValue(hiddentElementID);
}	

function SetQuestionIDInHiddenField(hiddentElementID){
  SetIDInHiddenField(hiddentElementID);
}	
function SetBackURL_notused(hyperLinkID){
  document.getElementById(hyperLinkID).href = document.getElementById(hyperLinkID).href + document.location.search;
}
function SetBackURL(hyperLinkID, newURL){
  document.getElementById(hyperLinkID).href = newURL + document.location.search;
}
function SetBackURLNoQueryString(hyperLinkID, newURL){
  document.getElementById(hyperLinkID).href = newURL ;
}
function isNull(anyData){
  if (anyData === null) {
    return true;
  }
  return false;
}
function SetQuestionNrInBackURL(hyperLinkID, keyValue,keyName){
  //alert(" isNull(GetQuerystringValue(keyName)) " + isNull(GetQuerystringValue(keyName)) );
  if (isNull(GetQuerystringValue(keyName))) {
    let urlStr = document.getElementById(hyperLinkID).href;
    if(urlStr.indexOf(".php") > -1){
      if(urlStr.indexOf(keyName) > -1){
        urlStr = urlStr.replace( `&i${keyName}`, `&i${keyName}ignore`);
      }
      urlStr = urlStr + `&i${keyName}=${keyValue}`;     
      document.getElementById(hyperLinkID).href = urlStr;  
    }
  }
}	
function HasQuestionBeenSaved(questionID){
  if (questionID > 0) {
    return true;
  } 
  return false;
}
function SaveFormData(hyperLinkID, formID, divName, keyName){
  //alert("Calling save");
  AlterPayload(divName);
  let frm = document.forms.namedItem(formID);
  let payload = $(frm).serializeFormJSON();
  //alert(JSON.stringify(payload));
  
  const resultObj = DivInitSaveFormData(divName, payload).then(()=>{
    if(IsValidJSONStringV2(document.getElementById(divName).innerText,"SaveFormData:528")){
      let divSourceObj =  JSON.parse(document.getElementById(divName).innerText);
      
      var firstKeyName = Object.keys(divSourceObj[0])[0];
      //alert("first key name: " + firstKeyName);
      var firstKeyValue = divSourceObj[0][firstKeyName]; // get value from first row
      //alert("first key value: " + firstKeyValue);
      //alert("questionID " + questionID);
      if (HasQuestionBeenSaved(firstKeyValue)) {
        SetQuestionNrInBackURL(hyperLinkID, firstKeyValue, firstKeyName);
            //alert(document.getElementById(hyperLinkID).href);
        document.getElementById(hyperLinkID).click(); 			
      }	
    }		
  });	
  
}

function shuffle(sourceArray) {
  for (var i = 0; i < sourceArray.length - 1; i++) {
      var j = i + Math.floor(Math.random() * (sourceArray.length - i));

      var temp = sourceArray[j];
      sourceArray[j] = sourceArray[i];
      sourceArray[i] = temp;
  }
  return sourceArray;
}

function ShuffleJSON_Number_Array(divName, arrayKey){

    if(IsValidJSONString(document.getElementById(divName).innerText)){
      //alert(jsonData[arrayKey]);
      if(document.getElementById(divName).innerText.indexOf(`"${arrayKey}"`) > -1){
      let jsonData = JSON.parse(document.getElementById(divName).innerText);
      //alert(jsonData[arrayKey]);
      jsonData[arrayKey] =  shuffle(jsonData[arrayKey]);
      document.getElementById(divName).innerHTML = JSON.stringify(jsonData);   
      }1

    }
 
}

function PopNumberAndMoveFromJSON_A_To_B(divName, arrayKeyPop, arrayKeyPush){

  if(IsValidJSONString(document.getElementById(divName).innerText)){
    //alert(jsonData[arrayKeyPop]);
    if(document.getElementById(divName).innerText.indexOf(`"${arrayKeyPop}"`) > -1){
      if(document.getElementById(divName).innerText.indexOf(`"${arrayKeyPush}"`) > -1){
        let jsonData = JSON.parse(document.getElementById(divName).innerText);

        //alert(jsonData[arrayKeyPop]);
        let poppedNr = jsonData[arrayKeyPop].pop();
        jsonData[arrayKeyPush].push(poppedNr);
        document.getElementById(divName).innerHTML = JSON.stringify(jsonData);   
        return poppedNr
      }   
    }
  }

  return -1;

}

async function DisplayQuestionV2(divName){
  let htmlRowTemplate = GetHtmlRowTemplate(divName);
  const resultObj =  DisplayDataInDivUsingJSONResponseFromDivAndFormData(divName, htmlRowTemplate).then(()=>{

  });     

  return resultObj;
}
