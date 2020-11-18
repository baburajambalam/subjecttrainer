<?php

    //__DIR__  document root
    include_once __DIR__ .'/../../db-helper.php';
    $sqlStatement = "";

  try
  {
    //open the database
    $db = new PDO(GetSQLite());

    //create the table
    $db->exec("CREATE TABLE SchoolSubject (ssuid INTEGER PRIMARY KEY, grade INTEGER,  subjectname TEXT, chapter TEXT)");    

    //insert some data...
    $db->exec("INSERT INTO SchoolSubject ( grade, chapter, subjectname) VALUES ( 2, 'Addition', 'Math');".
    "INSERT INTO SchoolSubject ( grade, chapter, subjectname) VALUES ( 7, 'Addition', 'Math');" .
    "INSERT INTO SchoolSubject ( grade, chapter, subjectname) VALUES ( 1, 'Plants', 'Science');" .
    "INSERT INTO SchoolSubject ( grade, chapter, subjectname) VALUES ( 7, 'Plant-Nutrition', 'Science');"
    );

    //create the table
    $db->exec("CREATE TABLE Question (qid INTEGER PRIMARY KEY, ssuid INTEGER, qtext TEXT, qAnswerFormula TEXT)");    

    //create the table
    $db->exec("CREATE TABLE QuestionVar (qvid INTEGER PRIMARY KEY, qid INTEGER, vPlaceHolder TEXT, vType TEXT)");    
    
    //create the table
    $db->exec("CREATE TABLE QuestionSet (qsid INTEGER PRIMARY KEY)");    
        
    //create the table
    $db->exec("CREATE TABLE QuestionSetQuestions (qsqid INTEGER PRIMARY KEY, qsid INTEGER, qid INTEGER)");    
    
    //create the table
    $db->exec("CREATE TABLE QuestionVarSet (qvsid INTEGER PRIMARY KEY, qsqid INTEGER, qvid INTEGER, numericValue REAL, textValue TEXT)");    
            
    //create the table
    $db->exec("CREATE TABLE QSQAnswerChoice (qsqacid INTEGER PRIMARY KEY, qsqid INTEGER, ans01 TEXT, ans02 TEXT, ans03 TEXT, ans04 TEXT, correctAns TEXT )");    
                
    //create the table
    $db->exec("CREATE TABLE student (studentID INTEGER PRIMARY KEY, studentName TEXT )");    
    
    //create the table
    $db->exec("CREATE TABLE studentQuestionSet (sqsid INTEGER PRIMARY KEY, studentID INTEGER, qsid INTEGER )");    
            
    //create the table
    $db->exec("CREATE TABLE CollectAnswer (qsqacid INTEGER PRIMARY KEY, caid INTEGER, studentID INTEGER, sqsid INTEGER, qsqid INTEGER, answer TEXT, IsCorrect TEXT, answerDtTime TEXT )");    
    

    //now output the data to a simple html table...
    print "<table border=1>";
    print "<tr>
    <td>ssuid</td>
    <td>grade</td>
    <td>term</td>
    <td>subjectname</td>
    </tr>";
    $result = $db->query('SELECT * FROM SchoolSubject');
    foreach($result as $row)
    {
      print "<tr><td>".$row['ssuid']."</td>";
      print "<td>".$row['grade']."</td>";
      print "<td>".$row['term']."</td>";
      print "<td>".$row['subjectname']."</td></tr>";
    }
    print "</table>";

    // close the database connection
    $db = NULL;
  }
  catch(PDOException $e)
  {
    print 'Exception : '.$e->getMessage();
  }
  catch (Exception $e) {
    print '{"Exception" : "'.$e->getMessage().'"}';
  }
?>