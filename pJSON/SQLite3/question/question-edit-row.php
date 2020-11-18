<?php

  //__DIR__  document root
  include_once __DIR__ .'\..\..\db-helper.php';
  $sqlStatment = "";

  try
  {
    $post_data = file_get_contents('php://input'); // Get Raw Posted Data
    $json = json_decode($post_data, true); // Decode it
    $sqlStatment = "update Question  set qtext='".GetString($json["qtext"])."', qAnswerFormula='".GetString($json["qAnswerFormula"])."' where qid=".GetNumber($json["qid"]).";";

    //open the database
    $db = new PDO('sqlite:C:\work_eDrive\baburaj\gitrepo\subjecttrainer\pJSON\SQLite3\subject_trainer.sqlite');

    //insert some data...
    $db->exec($sqlStatment);

    $db = NULL;

    $sqlStatment = "SELECT qid FROM Question where qid= :qid: ";
    $ColumnsToBeReturned = array(
      "qid"=>INTEGER
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