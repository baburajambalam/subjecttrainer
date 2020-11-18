<?php

  //__DIR__  document root
  include_once __DIR__ .'\..\..\db-helper.php';
  $sqlStatment = "";

  try{
  
    $ColumnsToBeReturned = array(
      "ssuid"=>INTEGER, 
      "grade"=>INTEGER, 
      "chapter"=>STRING, 
      "subjectname"=>STRING, 
      "ssuidListAll"=>STRING, 
      "qid"=>INTEGER, 
      "qcount"=>INTEGER
      );
  
    $sqlStatment = "SELECT 
    1 ssuid, '-' grade, '-' chapter, '-' subjectname , 
    0 qid,
    0 qcount
    ";
    $CurrentRowCount = PrintDataQueryAsJSON($sqlStatment,  $ColumnsToBeReturned);


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