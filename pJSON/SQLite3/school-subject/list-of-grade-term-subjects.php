<?php

  //__DIR__  document root
  include_once __DIR__ .'\..\..\db-helper.php';
  $sqlStatment = "";
  try
  {

    $sqlStatment = "SELECT 
      SchoolSubject.ssuid, SchoolSubject.grade, SchoolSubject.chapter, SchoolSubject.subjectname , 
      case 
        when max(Question.qid)is null then 0
        else max(Question.qid)
        end qid,
      count(Question.qid) qcount
    FROM 
      SchoolSubject 
      left join Question on Question.ssuid = SchoolSubject.ssuid
      where (SchoolSubject.ssuid= :ssuid:) or  ('ALL'=':ssuidListAll:')
    group by SchoolSubject.ssuid, SchoolSubject.grade, SchoolSubject.chapter, SchoolSubject.subjectname
    order by SchoolSubject.grade , SchoolSubject.subjectname ";

  //"SELECT QuestionVar.* FROM QuestionVar where  (qid=:qid: and qvid=:qvid:) or (qid=:qid: and 'ALL'=':qvidListType:' ) ";

  $ColumnsToBeReturned = array(
    "ssuid"=>INTEGER, 
    "grade"=>INTEGER, 
    "chapter"=>STRING, 
    "subjectname"=>STRING, 
    "ssuidListAll"=>STRING, 
    "qid"=>INTEGER, 
    "qcount"=>INTEGER
    );

    $CurrentRowCount = PrintDataQueryAsJSON($sqlStatment,  $ColumnsToBeReturned);

    if($CurrentRowCount == 0){
      $sqlStatment = "SELECT 
      :ssuid: ssuid, '-' grade, '-' chapter, '-' subjectname , 
      0 qid,
      0 qcount
      ";
      $CurrentRowCount = PrintDataQueryAsJSON($sqlStatment,  $ColumnsToBeReturned);
    }

  }
  catch(PDOException $e)
  {
    print '{"Exception" : "'.$e->getMessage().'"}';
  }
  catch (Exception $e) {
    print '{"Exception" : "'.$e->getMessage().'"}';
  }

?>