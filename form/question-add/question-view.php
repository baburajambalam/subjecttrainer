<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../css/bootstrap/4.3.1/css/bootstrap.min.css">

    <title>Hello, world!</title>
  </head>
  <body onload="start();">
    <h1>    
        <div id="DisplayAllSubjects" style="display:none;">
            {
                "url":"http://localhost:81/pJSON/SQLite3/school-subject/list-of-grade-term-subjects.php",
                "payload":{
                    "formID":"form_118102",
                    "action":"ShowAllSubjects"
                }
            }
        </div>    
    </h1>
    <div id="DisplayAllSubjectsTemplate" style="display:none;">
        Grade {grade} / {subjectname}/ chapter {chapter} / {qcount} questions / <a href='../../../trainer.php?i[[ssuid]]={ssuid}&i[[qid]]={qid}'>Trainer's page</a>
    </div>
    
		<form id="form_118102" class="appnitro"  method="post" action="">
            <input type="hidden" name="ssuid" id="ssuid" value="0" />	
            <input type="hidden" name="qid" id="qid" value="0" />        
            <input type="hidden" name="qvidListType" id="qvidListType" value="-" />        
        </form>	
    <br>
    <div id="DivDisplayPrevNext" style="display:none;">
        {
            "url":"http://localhost:81/pJSON/SQLite3/question/question-get-prev-next.php",
            "payload":{
                "formID":"form_118102",
                "action":"ShowPrevNext"
            }
        }
    </div>       
    <div id="DivDisplayPrevNextTemplate" style="display:none;">
        <table>
        <tr>
            <td><span style='visibility:{previousVisibility};'><a id="anchorPrevious2" href="#">Previous</a></span></td>
            <td>&nbsp; - &nbsp;</td>
            <td><a id="anchorEditCurrent2" href="#">Edit</a></td>
            <td>&nbsp; - &nbsp;</td>
            <td><span style='visibility:{nextVisibility};'><a id="anchorNext2" href="#">Next</a></span></td>
            <td>&nbsp; - &nbsp;</td>
            <td><a id="anchorNew2" href="#">Add-New-Question</a></td>        
        </tr>
        </table> 
    </div>
     <div id="DivDisplayQuestion" style="display:none;">
        {
            "url":"http://localhost:81/pJSON/SQLite3/question/question-get.php",
            "payload":{
                "formID":"form_118102",
                "action":"ShowQuestion"
            }
        }
    </div>       
    <div id="DivDisplayQuestionTemplate" style="display:none;">
        <table style='height: 387px;' width='583'>
        <tbody>
        <tr style='height: 40px;'>
            <td style='width: 573px; height: 40px;'>Grade {grade}  / {subjectname} / Chapter {chapter} / Question Nr {qid} </td>
        </tr>        
        <tr style='height: 122px;'>
            <td style='width: 573px; height: 122px;'><b>Question</b> <br><br>{qtext}</td>
        </tr>
        <tr style='height: 122px;'>
            <td style='width: 573px; height: 122px;'><b>Answer</b> <br><br>Formula {qAnswerFormula}</td>
        </tr>   
        </tbody>
        </table>
    </div>
    <br>
    <a id="AddQuestionVariableHyperLink" href="J#" onclick="winOpen(this.href);return false;">Add variable</a><br> 
    <br>
    <div id="DivDisplayQuestionVariable" style="display:none;">
        {
            "url":"http://localhost:81/pJSON/SQLite3/question/question-variable-get.php",
            "payload":{
                "formID":"form_118102",
                "action":"ShowQuestion"
            },
            "alterpayload":{
                "qvidListType":"ALL"
            }            
        }
    </div>  
    <div id="DivDisplayQuestionVariableTemplate" style="display:none;">
        <p>Variable ##<span style="color: #ff0000;"><strong>{vPlaceHolder}</strong></span>## of type <span style="color: #ff0000;"><strong>{vType}</strong></span>&nbsp;<a href="question-variable-add.php?iqid={qid}&iqvid={qvid}" onclick="winOpen(this.href);return false;" >Edit</a></p>
    </div>
    <!-- JavaScript -->
	<script src="../../js/subjectrainerjsfile/jquery-3.5.1.min.js"  crossorigin="anonymous"></script>    
    <script src="../../js/subjectrainerjsfile/st.js"  crossorigin="anonymous"></script>
    <script> 
    function start(){

        SetQuestionIDInHiddenField("ssuid");
        SetQuestionIDInHiddenField("qid");

        SetPreviousAndNextAnchor2("qid", "anchorPrevious2", "anchorNext2", "ssuid", "anchorNew2"); 

        SetEditAnchor("qid", "anchorEditCurrent2");
        

        DisplayQuestion("DisplayAllSubjects");
        


        DisplayQuestion("DivDisplayPrevNext");


        DisplayQuestion("DivDisplayQuestion");



        DisplayQuestion("DivDisplayQuestionVariable");



        SetAddQuestionVariableAnchor("AddQuestionVariableHyperLink");


    }

    async function DisplayQuestion(divName){
        document.getElementById(divName).style["display"]="none"; 
        let DivDisplayTemplate = `${divName}Template`;
        let htmlRowTemplate = document.getElementById(DivDisplayTemplate).innerHTML;
        document.getElementById(DivDisplayTemplate).innerHTML="";
        //let htmlRowTemplate = "Question {qtext}/ Term {term} / Subject {subjectname} / <a href='./form/question-add/form.php?i[[ssuid]]={ssuid}&action=add-question'>Add-Question</a>";
        DisplayDataInDivUsingJSONResponseFromDivAndFormData(divName, htmlRowTemplate);      
        document.getElementById(divName).style["display"]="block";  
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

    function winOpen(URL){
        window.open(URL,'targetWindow', `toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=800,height=400`)
    }

    function windowReload(){
        document.location.href = document.location.href;
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
 