<?php

  //__DIR__  document root
  include_once __DIR__ .'\..\..\db-helper.php';
  $sqlStatment = "";

  try
  {

    $sqlStatment = "SELECT 
                      Question.qid, Question.ssuid, Question.qtext, Question.qAnswerFormula, 
                      SchoolSubject.grade, SchoolSubject.chapter, SchoolSubject.subjectname 
                    FROM 
                      Question 
                      join SchoolSubject on SchoolSubject.ssuid = Question.ssuid
                    where 
                      qid = :qid: ";

    $ColumnsToBeReturned = array(
      "qid"=>INTEGER, 
      "ssuid"=>INTEGER, 
      "qtext"=>STRING, 
      "qAnswerFormula"=>STRING, 
      "grade"=>STRING, 
      "chapter"=>STRING, 
      "subjectname"=>STRING
      );


      $CurrentRowCount =PrintDataQueryAsJSON($sqlStatment,  $ColumnsToBeReturned);
    
      if($CurrentRowCount == 0){
        $sqlStatment = "SELECT 
        0 qid, :ssuid: ssuid, '-' qtext, '-' qAnswerFormula, 
        0 grade, '-' chapter, '-' subjectname
        ";
        $CurrentRowCount = PrintDataQueryAsJSON($sqlStatment,  $ColumnsToBeReturned);
      }      

  }
  catch(PDOException $e)
  {
    print '[';
        print '{';
            //print GetJSONKeyValue("sqlStatment", $sqlStatment, $const_value_enclosed_in_quotes, $const_comma_required);
            print '"Exception" : "'.$e->getMessage().'"';          
        print '}';
    print ']';
  }
?>