<?php

    //__DIR__  document root
    include_once __DIR__ .'\..\..\db-helper.php';

  try
  {
    $post_data = file_get_contents('php://input'); // Get Raw Posted Data
    $json = json_decode($post_data, true); // Decode it

     //print json_encode($json);

     //print $json["ssuid"];

    //$sqlStatement = "INSERT INTO Question ( ssuid, qtext, qAnswerFormula) VALUES ( '".$json["ssuid"]."', qtext, qAnswerFormula);";

    
    $sqlStatment = "INSERT INTO Question ( ssuid, qtext, qAnswerFormula) VALUES ( ".GetNumber($json["ssuid"]).", '".GetString($json["qtext"])."', '".GetString($json["qAnswerFormula"])."');";

    //print $sqlStatment;

    //insert some data...
    //$db->exec($sqlStatment );

    //$result = $db->query('SELECT max(qid) qid FROM Question  ');

    print '[';
    //$rowcount = 0;

    //foreach($result as $row)
    //{
        //print GetComma($rowcount > 0);
        print '{';
            print GetJSONKeyValue( "sqlstatement", $sqlStatment, $const_value_enclosed_in_quotes, $const_comma_not_required);
        print '}';

        //$rowcount  = $rowcount  + 1;
    //}
    print ']';

    //$post_data = file_get_contents('php://input'); // Get Raw Posted Data
    //$json = json_decode($post_data, true); // Decode it


    //print json_encode($_POST); //JSON data is not available in the $_POST variable

    //print json_encode($post_data);

    //$db = NULL;
  }
  catch(PDOException $e)
  {
    print '{"Exception" : "'.$e->getMessage().'"}';
  }
  catch (Exception $e) {
    print '{"Exception" : "'.$e->getMessage().'"}';
  }
?>