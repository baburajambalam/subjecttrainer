<?php

    //__DIR__  document root
    include_once __DIR__ .'\..\..\db-helper.php';
    $sqlStatement = "";
  try
  {
    $ColumnsToBeReturned = array(
        "ssuid"=>INTEGER, 
        "grade"=>INTEGER, 
        "subjectname"=>STRING, 
        "chapter"=>STRING, 
        "actiondelete"=>INTEGER
        );
  
    $DeleteStatement = "DELETE FROM SchoolSubject where grade=:grade: AND subjectname=':subjectname:' AND chapter=':chapter:' AND ssuid=:ssuid: "; 
    $UpdateStatement = "UPDATE SchoolSubject set grade=:grade:, subjectname=':subjectname:', chapter=':chapter:' where ssuid=:ssuid: and  ssuid>0 "; 
    $InsertStatement = ""; //"INSERT INTO SchoolSubject ( grade, subjectname, chapter) VALUES ( :grade:, ':subjectname:', ':chapter:') ";      

    $primaryKeyName="ssuid";
    $boolResult = ExecuteDMLInsertUpdateDelete($InsertStatement, $UpdateStatement, $DeleteStatement, $ColumnsToBeReturned, $primaryKeyName);

    if($boolResult == True){

        $ColumnsToBeReturned = array(
            "ssuid"=>INTEGER
            );
        $sqlStatement = "SELECT max(ssuid) ssuid FROM SchoolSubject ";

        PrintDataQueryAsJSON($sqlStatement,  $ColumnsToBeReturned);

    }else{
      print '[';
      print '{';
        print GetJSONKeyValue("boolResult", json_encode($boolResult), const_value_enclosed_in_quotes, const_comma_not_required);
      print '}';
    print ']';      
    }
  }
  catch(PDOException $e)
  {
    print '[';
      print '{';
        print GetJSONKeyValue("boolResult", json_encode($boolResult), const_value_enclosed_in_quotes, const_comma_required);
        print GetJSONKeyValue("sqlStatement", json_encode($sqlStatement), const_value_enclosed_in_quotes, const_comma_required);
        print '"Exception" :'.json_encode($e->getMessage()).'';          
      print '}';
    print ']';
  }
?>