<?php

    //__DIR__  document root
    include_once __DIR__ .'/../../db-helper.php';

  try
  {
    $post_data = file_get_contents('php://input'); // Get Raw Posted Data
    $json = json_decode($post_data, true); // Decode it

    $sqlStatment = "SELECT 
                      Question.qid, Question.ssuid, Question.qtext, Question.qAnswerFormula, 
                      SchoolSubject.grade, SchoolSubject.term, SchoolSubject.subjectname 
                    FROM 
                      Question 
                      join SchoolSubject on SchoolSubject.ssuid = Question.ssuid
                    where 
                      qid = ".GetNumber($json["qid"]).";";

    //open the database
    $db = new PDO(GetSQLite());

    $result = $db->query($sqlStatment);

    print '[';
    $rowcount = 0;

    foreach($result as $row)
    {
        print GetComma($rowcount > 0);
        print '{';
            print GetJSONKeyValueFromRow($row, "qid", $const_value_not_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValueFromRow($row, "ssuid", $const_value_not_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValueFromRow($row, "qtext",  $const_value_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValueFromRow($row, "qAnswerFormula", $const_value_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValueFromRow($row, "grade", $const_value_not_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValueFromRow($row, "term", $const_value_not_enclosed_in_quotes, $const_comma_required);
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