<?php

  //__DIR__  document root
  include_once __DIR__ .'/../../db-helper.php';
  $sqlStatment = "";
  try
  {
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
                          qid < :qid:
                          and ssuid = :ssuid:
                      ) QuestionPrev on 1=1   
                      left join (
                        SELECT 
                          min(Question.qid)  nextVal
                        FROM 
                          Question                       
                        where 
                          qid > :qid:
                          and ssuid = :ssuid:
                      ) QuestionNext on 1=1                     
                    where 
                      qid = :qid:
                      and ssuid = :ssuid:
                      ";

    $ColumnsToBeReturned = array(
      "qid"=>INTEGER, 
      "ssuid"=>INTEGER, 
      "previousVal"=>INTEGER, 
      "nextVal"=>INTEGER, 
      "previousVisibility"=>STRING, 
      "nextVisibility"=>STRING
      );


    $CurrentRowCount =PrintDataQueryAsJSON($sqlStatment,  $ColumnsToBeReturned);
    
    if($CurrentRowCount == 0){
      $sqlStatment = "SELECT 
      0 qid, :ssuid: ssuid, 0 previousVal, 'hidden' previousVisibility, 
      0 nextVal, 'hidden' nextVisibility
      ";
      $CurrentRowCount = PrintDataQueryAsJSON($sqlStatment,  $ColumnsToBeReturned);
    }

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