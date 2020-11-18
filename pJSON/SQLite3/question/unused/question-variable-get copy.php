<?php

    //__DIR__  document root
    include_once __DIR__ .'/../../db-helper.php';
    $sqlStatment = "";
  try
  {
    $post_data = file_get_contents('php://input'); // Get Raw Posted Data
    $json = json_decode($post_data, true); // Decode it
    
    $qid =  GetNumberFromArray($json, "qid");
    $qvid =  GetNumberFromArray($json, "qvid");
    $qvidListType = GetStringFromArray($json, "qvidListType");

      /*
    if($qvidListType == "ALL"){
      $sqlStatment = "SELECT * FROM QuestionVar where qid=:qid: ";
    }else{
      $sqlStatment = "SELECT * FROM QuestionVar where qid=:qid: and qvid=:qvid: ";
    } 
    */
    
    $sqlStatment = "SELECT * FROM QuestionVar where  (qid=:qid: and qvid=:qvid:) or (qid=:qid: and 'ALL'=':qvidListType:' ) ";

    $sqlStatment = SqlDataReplace(":qid:", $qid, $sqlStatment);
    $sqlStatment = SqlDataReplace(":qvid:", $qvid, $sqlStatment);
    $sqlStatment = SqlDataReplace(":qvidListType:", $qvidListType, $sqlStatment);

    //open the database
    $db = new PDO(GetSQLite());

    $result = $db->query($sqlStatment);

    print '[';
    $rowcount = 0;

    foreach($result as $row)
    {
        print GetComma($rowcount > 0);
        print '{';
            print GetJSONKeyValueFromRow($row, "qvid", $const_value_not_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValueFromRow($row, "qid", $const_value_not_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValueFromRow($row, "vPlaceHolder",  $const_value_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValueFromRow($row, "vType", $const_value_enclosed_in_quotes, $const_comma_not_required);
        print '}';

        $rowcount  = $rowcount  + 1;
    }
    print ']';

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