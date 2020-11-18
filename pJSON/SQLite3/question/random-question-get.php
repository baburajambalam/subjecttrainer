<?php

  //__DIR__  document root
  include_once __DIR__ .'/../../db-helper.php';
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

      //PrintDataQueryAsJSONTest($sqlStatment);

      //PrintDataQueryAsJSON($sqlStatment, $ColumnsToBeReturned);

      $rowdata=GetFirstRowAsArray($sqlStatment, $ColumnsToBeReturned);

      $sqlStatment = "SELECT vPlaceHolder, vType, qvid, qid, 
                      (ABS(RANDOM()) % (20 - 1) + 2)  rnNr
                      FROM QuestionVar where  qid=:qid: order by qvid ";

      $ColumnsToBeReturned = array(
        "vPlaceHolder"=>STRING,
        "vType"=>STRING,
        "qid"=>INTEGER, 
        "qvid"=>INTEGER, 
        "rnNr"=>INTEGER
        );

      $multiRow = GetDataAsArray($sqlStatment, $ColumnsToBeReturned);

      $qAnswerFormulaLocal="";
      foreach($multiRow as $rowdata2) { 
        $find = "##".$rowdata2["vPlaceHolder"]."##";
        $replaceWith = $rowdata2["rnNr"];
        $sourcestr = $rowdata["qtext"];
        $rowdata["qtext"] = str_replace($find, $replaceWith, $sourcestr); 

        $find = $rowdata2["vPlaceHolder"];
        $replaceWith = $rowdata2["rnNr"];
        $sourcestr = $rowdata["qAnswerFormula"];
        $rowdata["qAnswerFormula"] = str_replace($find, $replaceWith, $sourcestr); 

        $qAnswerFormulaLocal = $rowdata["qAnswerFormula"];

      } 
      $correctAnswer= 0;
      $qAnswerFormulaLocal = " \$correctAnswer = ". $qAnswerFormulaLocal.";";  
      eval( $qAnswerFormulaLocal);
      //print ("correctAnswer ".$correctAnswer);

      $answers = array($correctAnswer, $correctAnswer + GetRanNegPosNr(), $correctAnswer + GetRanNegPosNr(), $correctAnswer + GetRanNegPosNr());
      shuffle($answers); 


      print '[';
        print '{';
            //print GetJSONKeyValue("sqlStatment", $sqlStatment, $const_value_enclosed_in_quotes, $const_comma_required);
            print '"qid" : '.$rowdata["qid"].',';          
            print '"ssuid" : '.$rowdata["ssuid"].',';          
            print '"qtext" : '.json_encode($rowdata["qtext"]).',';          
            print '"qAnswerFormula" : '.json_encode($rowdata["qAnswerFormula"]).',';          
            print '"grade" : '.$rowdata["grade"].',';          
            print '"chapter" : '.json_encode($rowdata["chapter"]).',';          
            print '"subjectname" : '.json_encode($rowdata["subjectname"]).',';          
            print '"ans_a" : '.json_encode(array_pop($answers)).',';          
            print '"ans_b" : '.json_encode(array_pop($answers)).',';            
            print '"ans_c" : '.json_encode(array_pop($answers)).',';          
            print '"ans_d" : '.json_encode(array_pop($answers)).',';          
            print '"correctAnswer" : '.json_encode($correctAnswer).'';          
        print '}';
      print ']';


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