<?php

    //__DIR__  document root
    include_once __DIR__ .'\..\..\db-helper.php';

  try
  {
    $post_data = file_get_contents('php://input'); // Get Raw Posted Data
    $json = json_decode($post_data, true); // Decode it

    $qidPlaceHolder= GetNumber($json["qid"]);
    $ssuidPlaceHolder= GetNumber($json["ssuid"]);

    $sqlStatment = "SELECT 
                      Question.qid, Question.ssuid, 
                      case 
                        when QuestionPrev.previousVal is null then Question.qid
                        else QuestionPrev.previousVal
                        end previousVal, 
                      case 
                        when QuestionPrev.previousVal is null then 'hidden'
                        else 'visible'
                        end previousVisibility, 
                      case 
                        when QuestionNext.nextVal is null then Question.qid
                        else QuestionNext.nextVal
                        end nextVal, 
                      case 
                        when QuestionNext.nextVal is null then 'hidden'
                        else 'visible'
                        end nextVisibility                        
                    FROM 
                      Question
                      left join (
                        SELECT 
                          max(Question.qid)  previousVal
                        FROM 
                          Question                       
                        where 
                          qid < #qid#
                          and ssuid = #ssuid#
                      ) QuestionPrev on 1=1   
                      left join (
                        SELECT 
                          min(Question.qid)  nextVal
                        FROM 
                          Question                       
                        where 
                          qid > #qid#
                          and ssuid = #ssuid#
                      ) QuestionNext on 1=1                     
                    where 
                      qid = #qid#
                      and ssuid = #ssuid#
                      ;";


    $sqlStatment =  str_replace("#qid#", $qidPlaceHolder, $sqlStatment);
    $sqlStatment =  str_replace("#ssuid#", $ssuidPlaceHolder, $sqlStatment);

    //open the database
    $db = new PDO('sqlite:C:\work_eDrive\baburaj\gitrepo\subjecttrainer\pJSON\SQLite3\subject_trainer.sqlite');

    $result = $db->query($sqlStatment);

    print '[';
    $rowcount = 0;

    foreach($result as $row)
    {
        print GetComma($rowcount > 0);
        print '{';
            print GetJSONKeyValueFromRow($row, "qid", $const_value_not_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValueFromRow($row, "ssuid", $const_value_not_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValueFromRow($row, "previousVal",  $const_value_not_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValueFromRow($row, "nextVal", $const_value_not_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValueFromRow($row, "previousVisibility", $const_value_enclosed_in_quotes, $const_comma_required);
            print GetJSONKeyValueFromRow($row, "nextVisibility", $const_value_enclosed_in_quotes, $const_comma_not_required);
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