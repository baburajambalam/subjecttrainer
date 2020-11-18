<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../css/bootstrap/4.3.1/css/bootstrap.min.css">

    <title>Quiz</title>
  </head>
  <body onload="start();">
    <h1>Quiz</h1>
    
    <form id="form_118102" class="appnitro"  method="post" action="">
        <input type="hidden" name="ssuid" id="ssuid" value="1" />	
        <input type="hidden" name="qid" id="qid" value="0" />    
              
        <input type="hidden" name="qAnswerFormula" id="qAnswerFormula" value="-" />        
    </form>	
    <br>
    <div id="DivListOfQuestionsView" style="display:none;"></div>
    <div id="DivListOfQuestionsServiceData" style="display:none;"></div>    
    <div id="DivListOfQuestionsController" style="display:none;">
        {
            "url":"/pJSON/SQLite3/question/question-get-list.php",
            "payload":{
                "formID":"form_118102",
                "action":"ShowQuestion"
            },
            "DivForDisplayingResponse":"DivListOfQuestionsView",
            "DivForDisplayingResponseDisplay":"block",
            "responseViewFormatOption":"JSON_NUMBER_ARRAY",
            "DataFormatLevel01":"DivListOfQuestionsControllerTemplate1",
            "DataFormatLevel02":"DivListOfQuestionsControllerTemplate2",
            "ServiceDataLocation":"DivListOfQuestionsServiceData"
        }
    </div>
    <div id="DivListOfQuestionsControllerTemplate2" style="display:none;">{qid},</div>
    <div id="DivListOfQuestionsControllerTemplate1" style="display:none;">
    {
        "qidList":[{DataFormatLevel02}],
        "qidListViewed":[]
        }
    </div>

    <input type="button" value="Back" id="Back" name="Back" OnClick="LoadPreviousQuestion();">
    <input type="button" value="Next" id="Next" name="Next" OnClick="LoadNextQuestion();">

    <div id="DivDisplayQuestionView" style="display:none;"></div>

    <div id="DivDisplayQuestionServiceData" style="display:none;"></div>    
     <div id="DivDisplayQuestion" style="display:none;">
        {
            "url":"/pJSON/SQLite3/question/random-question-get.php",
            "payload":{
                "formID":"form_118102",
                "action":"ShowQuestion"
            },
            "setFormDataAfterGetServiceData":{
                "qAnswerFormula":"qAnswerFormula"
            },
            "DivForDisplayingResponse":"DivDisplayQuestionView",
            "DivForDisplayingResponseDisplay":"block",
            "responseViewFormatOption":"DEFAULT",
            "DataFormatLevel01":"DivDisplayQuestionTemplate",
            "ServiceDataLocation":"DivDisplayQuestionServiceData"            
        }
    </div>      
     
    <div id="DivDisplayQuestionTemplate" style="display:none;">

    <table style='height: 387px;' width='583' border="1">
        <tbody>
        <tr>
            <td style='width: 573px;'>Grade {grade}  / {subjectname} / Chapter: {chapter} / Question Nr {qid} </td>
        </tr>        
        <tr style='height: 122px;'>
            <td style='width: 573px; height: 122px;'><b>Question</b> <br><br>{qtext}</td>
        </tr>
        <tr>
            <td style="padding-left: 50px;">
                
                <b>Select the correct answer : </b>  
                <br>
                <input type="radio" id="chk_A" name="selectedAnswer" value="{ans_a}" onclick="CheckAnswer(this);" > 
                <span id="span_chk_A" style=""> {ans_a} </span>
                <br>
                <input type="radio" id="chk_B" name="selectedAnswer" value="{ans_b}" onclick="CheckAnswer(this);" > 
                <span id="span_chk_B" style=""> {ans_b} </span>
                <br>
                <input type="radio" id="chk_C" name="selectedAnswer" value="{ans_c}" onclick="CheckAnswer(this);" >  
                <span id="span_chk_C" style=""> {ans_c} </span>
                <br>
                <input type="radio" id="chk_D" name="selectedAnswer" value="{ans_d}" onclick="CheckAnswer(this);" > 
                <span id="span_chk_D" style=""> {ans_d} </span>
                <input type="hidden" name="answer" id="answer" value="{correctAnswer}" />  
            </td>
        </tr>   
        </tbody>
        </table>        
    </div>
    <br>
 

    <!-- JavaScript -->
	<script src="../../js/subjectrainerjsfile/jquery-3.5.1.min.js"  crossorigin="anonymous"></script>    
    <script src="../../js/subjectrainerjsfile/st.js"  crossorigin="anonymous"></script>
    <script> 
    function CheckAnswer(rad){
        let selectedval = rad.value;
        let correctval =  document.getElementById("answer").value;
        //alert("span_" + rad.id);
        if(selectedval == correctval){

            document.getElementById("span_" + rad.id).style.color = "green";
            document.getElementById("span_" + rad.id).style.backgroundColor = "yellow";
            document.getElementById("span_" + rad.id).innerHTML =  document.getElementById("span_" + rad.id).innerHTML + "<span color='brown'> Yeah! You got it right. </span>"
        }
        else{
            document.getElementById("span_" + rad.id).style.color = "red";
            document.getElementById("span_" + rad.id).innerHTML =  document.getElementById("span_" + rad.id).innerHTML + "<span color='red'> Wrong ! </span>"
        }
    }

    function LoadPreviousQuestion(){
        let divName="DivListOfQuestionsView";
        let arrayKeyPop="qidListViewed";
        let arrayKeyPush="qidList";
        let nextQuestion = PopNumberAndMoveFromJSON_A_To_B(divName, arrayKeyPop, arrayKeyPush);
        if(nextQuestion > 0){
            document.getElementById("qid").value=nextQuestion;
            DisplayQuestion("DivDisplayQuestion");           
        }        
    }

    function LoadNextQuestion(){
        let divName="DivListOfQuestionsView";
        let arrayKeyPop="qidList";
        let arrayKeyPush="qidListViewed";
        let nextQuestion = PopNumberAndMoveFromJSON_A_To_B(divName, arrayKeyPop, arrayKeyPush);
        if(nextQuestion > 0){
            document.getElementById("qid").value=nextQuestion;
            DisplayQuestion("DivDisplayQuestion");           
        }
    }

    function start(){
        const resultObj =  DisplayQuestionV2("DivListOfQuestionsController").then(()=>{
            ShuffleJSON_Number_Array("DivListOfQuestionsView", "qidList");           
        }).then(()=>{
            LoadNextQuestion();
        })
        
        ;	

        /*
        SetQuestionIDInHiddenField("ssuid");
        SetQuestionIDInHiddenField("qid");

        SetPreviousAndNextAnchor2("qid", "anchorPrevious2", "anchorNext2", "ssuid", "anchorNew2"); 

        SetEditAnchor("qid", "anchorEditCurrent2");
        

  


        DisplayQuestion("DivDisplayPrevNext");


        DisplayQuestion("DivDisplayQuestion");



        DisplayQuestion("DivDisplayQuestionVariable");



        SetAddQuestionVariableAnchor("AddQuestionVariableHyperLink");
        */
        return resultObj;
    }

    async function DisplayQuestion(divName){
        //document.getElementById(divName).style["display"]="none"; 
        let htmlRowTemplate = GetHtmlRowTemplate(divName);
        const resultObj =  DisplayDataInDivUsingJSONResponseFromDivAndFormData(divName, htmlRowTemplate).then(()=>{
      

            });	      
        //document.getElementById(divName).style["display"]="none";  

        return resultObj;
    }

    function SetEditAnchor(qsKey, anchorEditCurrent){
        let currentLocation = document.location.href.slice(0);
        //alert("currentLocation = " + currentLocation);
     
        let editLocation = currentLocation.replace(`question-view.php`, `form-edit.php`);

        document.getElementById(anchorEditCurrent).href = editLocation;
    
    }

    function SetAddQuestionVariableAnchor(anchorAdd){
        let currentLocation = document.location.href.slice(0);
        //alert("currentLocation = " + currentLocation);
     
        let editLocation = currentLocation.replace(`question-view.php`, `question-variable-add.php`);

        //let anchorAddHref = document.getElementById(anchorAdd).href ;
        document.getElementById(anchorAdd).href = editLocation; //anchorAddHref.replace("URL", editLocation);
    
    }

    function SetPreviousAndNextAnchor2(qsKey, anchorPrevious, anchorNext, ssuid, anchorNew){
        let qsVal = parseInt(GetQuerystringValue(qsKey));
        let ssuidVal = parseInt(GetQuerystringValue(ssuid));
        //alert("qsVal = " + qsVal);
        //let nextVal = qsVal + 1;
        //alert("nextVal = " + nextVal);
        //let previousVal = qsVal - 1;

        let currentLocation = document.location.href.slice(0);
        //alert("currentLocation = " + currentLocation);
     
        //i[[ssuid]]={ssuid}

        let previousLocation = currentLocation.replace(`i${qsKey}=${qsVal}`, `i[[${qsKey}]]={previousVal}`);
       
        //let previousLocation = currentLocation.replace(`i${qsKey}=${qsVal}`, `i${qsKey}=${previousVal}`);
       
         // alert("previousLocation = " + previousLocation);
         let nextLocation = currentLocation.replace(`i${qsKey}=${qsVal}`, `i[[${qsKey}]]={nextVal}`);
         //let nextLocation = currentLocation.replace(`i${qsKey}=${qsVal}`, `i${qsKey}=${nextVal}`);

        document.getElementById(anchorPrevious).href = previousLocation;
        document.getElementById(anchorNext).href = nextLocation;
     
        let addNewLocation = `form.php?i${ssuid}=${ssuidVal}&action=add-question`;
        document.getElementById(anchorNew).href = addNewLocation;
    }    
   
    </script>

</body>
</html>
 