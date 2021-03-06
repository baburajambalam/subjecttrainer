<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Add grade, subject & chapter</title>
<link rel="stylesheet" type="text/css" href="view.css" media="all">
<script type="text/javascript" src="view.js"></script>

</head>
<body id="main_body" onload="start();">
	
	<img id="top" src="top.png" alt="">
	<div id="form_container">
	
		<h1></h1>
		<a id="BackHyperLink" href="#">Back</a>        
		<form id="form_118102" class="appnitro"  method="post" action="">
					<div class="form_description">
			<h2>Add grade, subject & chapter</h2>
			<p>Questions will be added under each chapter</p>
		</div>						
			<ul >
			
					<li id="li_2" >
		<label class="description" for="grade">Grade </label><div id="gradeCurrentData" name="gradeCurrentData"></div>
		<div>
		<select class="element select medium" id="grade" name="grade"> 
			<option value="" selected="selected"></option>
            <option value="1" >Grade 1</option>
            <option value="2" >Grade 2</option>
            <option value="3" >Grade 3</option>
            <option value="4" >Grade 4</option>
            <option value="5" >Grade 5</option>
            <option value="6" >Grade 6</option>
            <option value="7" >Grade 7</option>
            <option value="8" >Grade 8</option>
            <option value="9" >Grade 9</option>
            <option value="10" >Grade 10</option>
            <option value="11" >Grade 11</option>
            <option value="12" >Grade 12</option>
		</select>
		</div><p class="guidelines" id="guide_2"><small>Select the grade. It is a required field.</small></p> 
		</li>		<li id="li_3" >
		<label class="description" for="subjectname">Subject </label><div id="subjectnameCurrentData" name="subjectnameCurrentData"></div>
		<div>
		<select class="element select medium" id="subjectname" name="subjectname"> 
			<option value="" selected="selected"></option>
            <option value="Mathematics" >Mathematics</option>
            <option value="Science" >Science</option>
            <option value="Social Studies" >Social Studies</option>
            <option value="English" >English</option>
            <option value="Computer Science" >Computer Science</option>
            <option value="Hindi" >Hindi</option>
            <option value="French" >French</option>
            <option value="General Knowledge" >General Knowledge</option>
		</select>
		</div><p class="guidelines" id="guide_3"><small>Select the subject. It is a required field.</small></p> 
		</li>		<li id="li_1" >
		<label class="description" for="chapter">Chapter </label><div id="chapterCurrentData" name="chapterCurrentData"></div>
		<div>
			<input id="chapter" name="chapter" class="element text medium" type="text" maxlength="255" value=""/> 
		</div><p class="guidelines" id="guide_1"><small>Write the chapter name. e.g.: Addition, Subtraction</small></p> 
		</li>
			
					<li class="buttons">
			    <input type="hidden" name="form_id" value="118102" />
				<input type="hidden" name="ssuid" id="ssuid" value="0" />	
				<input type="hidden" name="ssuidListAll" id="ssuidListAll" value="single" />	
				<input id="saveForm" class="button_text" type="button" name="submit" value="Submit" onClick="SaveFormData('BackHyperLink', 'form_118102', 'DivSaveFormDataAsChapter', 'ssuid');"/>
		</li>
			</ul>
		</form>	


		<div id="DivSaveFormDataAsChapter" style="display:none">
        {
			"url":"/pJSON/SQLite3/school-subject/chapter-variable-add-new-row.php",
			"action":"SaveFormDataAndGetNewRowID",
            "payload":{
                "action":"SaveFormDataAndGetNewRowID"
            }
        }
    	</div>

		<div id="DivDeleteFormDataAsChapter" style="display:none">
        {
			"url":"/pJSON/SQLite3/school-subject/chapter-variable-add-new-row.php",
			"action":"DeleteFormDataAndRowGetRowID",
            "payload":{
                "action":"DeleteFormDataAndRowGetRowID"
            },
            "alterpayload":{
                "actiondelete":1
            }  
        }
    	</div>		

		
		<div id="DivDisplayFormDataAsChapter" style="display:none">
        {
            "url":"/pJSON/SQLite3/school-subject/list-of-grade-term-subjects.php",
            "payload":{
                "formID":"form_118102",
				"action":"QueryData"
            },
            "formelements":{
				"ssuid":"hidden",
				"grade":"select",
				"subjectname":"select",
				"chapter":"text"
            }			
        }
    	</div>  
		
		
		<div id="footer">
			Generated by <a href="http://www.phpform.org">pForm</a>
		</div>
	</div>
	<img id="bottom" src="bottom.png" alt="">
	</body>
	<script src="../../js/subjectrainerjsfile/jquery-3.5.1.min.js"  crossorigin="anonymous"></script>
	<script src="../../js/subjectrainerjsfile/st.js"  crossorigin="anonymous"></script>
	
    <script> 
    function start(){
		let primaryKeyName = "ssuid";
		SetBackURLNoQueryString("BackHyperLink","javascript:window.opener.windowReload();window.close();");
		SetIDInHiddenField(primaryKeyName);
		if( parseInt(document.getElementById(primaryKeyName).value) > 0 ){
			DisplayDataInFrom("DivDisplayFormDataAsChapter");			
		}
	}

    </script>	
</html>