<?php

    //__DIR__  document root
    include_once __DIR__ .'/../../db-helper.php';
    $sqlStatment = "";
  try
  {
    $post_data = file_get_contents('php://input'); // Get Raw Posted Data
    $json = json_decode($post_data, true); // Decode it

    $qid =  GetNumberFromArray($json, "qid");
    $vPlaceHolder = GetStringFromArray($json, "vPlaceHolder");
    $vType = GetStringFromArray($json, "vType");
    $qvid =  GetNumberFromArray($json, "qvid");
    $actiondelete = GetNumberFromArray($json, "actiondelete");

    if($actiondelete == 1){
      $sqlStatment = "DELETE FROM QuestionVar where qvid=:qvid: AND vPlaceHolder=':vPlaceHolder:' AND vType=':vType:' AND qid=:qid: "; 
    }elseif($qvid > 0){
      $sqlStatment = "UPDATE QuestionVar set vPlaceHolder=':vPlaceHolder:', vType=':vType:', qid=:qid: where qvid=:qvid:"; 
    }else{
      $sqlStatment = "INSERT INTO QuestionVar ( qid, vPlaceHolder, vType) VALUES ( :qid:, ':vPlaceHolder:', ':vType:') ";      
    }

    $sqlStatment = SqlDataReplace(":qid:", $qid, $sqlStatment);
    $sqlStatment = SqlDataReplace(":vPlaceHolder:", $vPlaceHolder, $sqlStatment);
    $sqlStatment = SqlDataReplace(":vType:", $vType, $sqlStatment);
    $sqlStatment = SqlDataReplace(":qvid:", $qvid, $sqlStatment);

    //open the database
    $db = new PDO(GetSQLite());

    //insert some data...
    $db->exec($sqlStatment);

    if($actiondelete == 1){
      $result = $db->query($sqlStatment);
      if ($result === TRUE) {
        print '[';
          print '{';
            print GetJSONKeyValue("delete", "Delete failed", $const_value_enclosed_in_quotes, $const_comma_required);        
            print GetJSONKeyValue("delSQL", $sqlStatment, $const_value_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValue("actiondelete", $actiondelete, $const_value_not_enclosed_in_quotes, $const_comma_not_required);        
            print '}';
        print ']';
      }else{
        print '[';
          print '{';
            print GetJSONKeyValue("qvid", $qvid, $const_value_not_enclosed_in_quotes, $const_comma_required);        
            print GetJSONKeyValue("delSQL", $sqlStatment, $const_value_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValue("actiondelete", $actiondelete, $const_value_not_enclosed_in_quotes, $const_comma_not_required);        
            print '}';
        print ']';
      }

    }
    else{
      $sqlStatementSelect = "SELECT max(qvid) qvid FROM QuestionVar where qid = :qid: ";
      $sqlStatementSelect = SqlDataReplace(":qid:", $qid, $sqlStatementSelect);

      $result = $db->query($sqlStatementSelect);

      print '[';
      $rowcount = 0;

      foreach($result as $row)
      {
          print GetComma($rowcount > 0);
          print '{';
            print GetJSONKeyValueFromRow($row, "qvid", $const_value_not_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValue("insSQL", $sqlStatment, $const_value_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValue("selectSQL", $sqlStatementSelect, $const_value_enclosed_in_quotes, $const_comma_not_required);
            print '}';

          $rowcount  = $rowcount  + 1;
      }
      print ']';
    }

    $db = NULL;
 

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