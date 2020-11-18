<?php
  try
  {
    //open the database
    $db = new PDO('sqlite:C:\work_eDrive\baburaj\gitrepo\subjecttrainer\pJSON\SQLite3\subject_trainer.sqlite');

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
      print "<td>".$row['chapter']."</td>";
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
?>