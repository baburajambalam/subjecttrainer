<?php

    //__DIR__  document root
    include_once __DIR__ .'/../../db-helper.php';

  try
  {
    //open the database
    $db = new PDO(GetSQLite());

    //insert some data...
    //$db->exec("INSERT INTO SchoolSubject ( grade, term, subjectname) VALUES ( 2, 1, 'Math');");

        
    $result = $db->query('SELECT * FROM SchoolSubject order by grade, term ');

    //create an array
    //$emparray = array();
    //foreach($result as $row){
    //    $emparray[] = $row;
    //}
    //print json_encode($dataarray);
    // close the database connection 

    print '[';
    $rowcount = 0;

    foreach($result as $row)
    {
        print GetComma($rowcount > 0);
        print '{';
            print GetJSONKeyValueFromRow($row, "ssuid", $const_value_not_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValueFromRow($row, "grade", $const_value_not_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValueFromRow($row, "chapter",  $const_value_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValueFromRow($row, "subjectname", $const_value_enclosed_in_quotes, $const_comma_not_required);
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
  catch (Exception $e) {
    print '{"Exception" : "'.$e->getMessage().'"}';
  }
?>