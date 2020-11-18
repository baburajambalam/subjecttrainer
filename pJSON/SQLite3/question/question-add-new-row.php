<?php

    //__DIR__  document root
    include_once __DIR__ .'\..\..\db-helper.php';

  try
  {
    $post_data = file_get_contents('php://input'); // Get Raw Posted Data
    $json = json_decode($post_data, true); // Decode it
    $sqlStatment = "INSERT INTO Question ( ssuid, qtext, qAnswerFormula) VALUES ( ".GetNumber($json["ssuid"]).", '".GetString($json["qtext"])."', '".GetString($json["qAnswerFormula"])."');";

    //open the database
    $db = new PDO('sqlite:C:\work_eDrive\baburaj\gitrepo\subjecttrainer\pJSON\SQLite3\subject_trainer.sqlite');

    //insert some data...
    $db->exec($sqlStatment);

    $result = $db->query('SELECT max(qid) qid FROM Question  ');

    print '[';
    $rowcount = 0;

    foreach($result as $row)
    {
        print GetComma($rowcount > 0);
        print '{';
            print GetJSONKeyValueFromRow($row, "qid", $const_value_not_enclosed_in_quotes, $const_comma_not_required);
        print '}';

        $rowcount  = $rowcount  + 1;
    }
    print ']';

    $db = NULL;
  }
  catch(PDOException $e)
  {
    print '{"Exception" : "'.$e->getMessage().'"}';
  }
?>