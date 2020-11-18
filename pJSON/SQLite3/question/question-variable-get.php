<?php

  //__DIR__  document root
  include_once __DIR__ .'/../../db-helper.php';
  $sqlStatment = "";
  try
  {
    
    $sqlStatment = "SELECT QuestionVar.* FROM QuestionVar where  (qid=:qid: and qvid=:qvid:) or (qid=:qid: and 'ALL'=':qvidListType:' ) ";

    $ColumnsToBeReturned = array(
      "qid"=>INTEGER, 
      "qvid"=>INTEGER, 
      "qvidListType"=>STRING, 
      "vPlaceHolder"=>STRING, 
      "vType"=>STRING
      );

    PrintDataQueryAsJSON($sqlStatment,  $ColumnsToBeReturned);

  }
  catch(PDOException $e)
  {
    print '[';
      print '{';
          print GetJSONKeyValue("sqlStatment", $sqlStatment, $const_value_enclosed_in_quotes, $const_comma_required);
          print '{"Exception" : "'.$sqlStatment."'  '".$e->getMessage().'"}';          
      print '}';
    print ']';
  }
?>