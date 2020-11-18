<?php

  //__DIR__  document root
  include_once __DIR__ .'/../../db-helper.php';
  $sqlStatment = "";

  try
  {

    $sqlStatment = "SELECT 
                      Question.qid
                    FROM 
                      Question 
                      join SchoolSubject on SchoolSubject.ssuid = Question.ssuid
                    where 
                    Question.ssuid = :ssuid: ";

    $ColumnsToBeReturned = array(
      "qid"=>INTEGER, 
      "ssuid"=>INTEGER, 
      "qtext"=>STRING, 
      "qAnswerFormula"=>STRING, 
      "grade"=>STRING, 
      "chapter"=>STRING, 
      "subjectname"=>STRING
      );


    PrintDataQueryAsJSON($sqlStatment,  $ColumnsToBeReturned);

  }
  catch(Exception $e)
  {
    print '[';
        print '{';
            print GetJSONKeyValue("sqlStatment", $sqlStatment, $const_value_enclosed_in_quotes, $const_comma_required);
            print '{"Exception" : "'.$sqlStatment."'  '".$e->getMessage().'"}';          
        print '}';
    print ']';
  }
?>