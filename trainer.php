<?php 
    include __DIR__ ."/pJSON/payload-keys.php"
    
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body onload="start();">
    <h1>Grades, Subjects & Chapters</h1>

    <a href="/form/grade_subject-chapter/add-grade-subject-chapter.php" onclick="winOpen(this.href);return false;" >New</a>

    <div id="DisplayAllSubjectsView" style="display:none;"></div>
    <div id="DisplayAllSubjectsServiceData" style="display:none;"></div>    
    <div id="DisplayAllSubjects" style="display:none;">
        {
            "url":"/pJSON/SQLite3/school-subject/list-of-grade-term-subjects.php",
            "payload":{
                "formID":"form_118102",
				"action":"QueryData"
            },
            "DivForDisplayingResponse":"DisplayAllSubjectsView",
            "DivForDisplayingResponseDisplay":"block",
            "responseViewFormatOption":"NONE",
            "DataFormatLevel01":"DivListOfQuestionsControllerTemplate1",
            "ServiceDataLocation":"DisplayAllSubjectsServiceData"
        }
    </div>
    <div id="DivListOfQuestionsControllerTemplate1" style="display:none;">
        <div>
            Grade {grade}/{subjectname} /  Chapter: {chapter} /  {qcount} questions /<a href='./form/question-add/question-view.php?i[[ssuid]]={ssuid}&i[[qid]]={qid}'>View Questions</a> / <a href='./form/grade_subject-chapter/add-grade-subject-chapter.php?i[[ssuid]]={ssuid}'  onclick="winOpen(this.href);return false;" >Edit</a>
        <div>
    </div>

    <div id="displaydataHTML">
        <form id="form_118102" action="">
        <input type="hidden" name="ssuid" id="ssuid" value="0" />	
        <input type="hidden" name="ssuidListAll" id="ssuidListAll" value="ALL" />	
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="/js/subjectrainerjsfile/st.js"  crossorigin="anonymous"></script>
    <script> 

    function start(){
        
        const resultObj =  DisplayQuestionV2("DisplayAllSubjects").then(()=>{
            let a=1;          
        });

    }

    </script>

</body>
</html>
 