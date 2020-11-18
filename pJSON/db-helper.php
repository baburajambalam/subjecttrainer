<?php
define("INTEGER", "INTEGER");
define("STRING", "STRING");

define("const_comma_required", true);
define("const_comma_not_required", false);
define("const_value_enclosed_in_quotes", true);
define("const_value_not_enclosed_in_quotes", false);

$const_comma_required = true;
$const_comma_not_required = false;
$const_value_enclosed_in_quotes = true;
$const_value_not_enclosed_in_quotes = false;


function GetSQLite(){
    if ($_SERVER['HTTP_HOST'] =="localhost:81") {
        return 'sqlite:C:\work_eDrive\baburaj\gitrepo\subjecttrainer\pJSON\SQLite3\subject_trainer.sqlite';
    }

    if ($_SERVER['HTTP_HOST'] =="subject-trainer.a-goldmine.com") {
        return 'sqlite:'.__DIR__.'/../pJSON/SQLite3/subject_trainer.sqlite';
    }

    return "-";
}

function GetComma($AddComma){
    if ($AddComma) {
        return ',';
    }
    return '';
}

function GetDoubleQuote($EncloseValueInQuotes){
    if ($EncloseValueInQuotes) {
        return '"';
    }
    return '';
}

function GetJSONKeyValue($keyName, $keyValue, $EncloseValueInQuotes, $AddComma)
{
    return '"'.$keyName.'": '.json_encode($keyValue).GetComma($AddComma);
}

function GetJSONKeyValueFromRow($myRow, $keyName, $EncloseValueInQuotes, $AddComma)
{
    if(!isset($myRow[$keyName])){
        //ignore $EncloseValueInQuotes
        return '"'.$keyName.'": "-"'.GetComma($AddComma); // column is not present in the result set since it is not part of the sql statement      
    }
    else{
        return '"'.$keyName.'": '.json_encode($myRow[$keyName]).GetComma($AddComma);        
    }
}

function GetKeyValueFromRow($myRow, $keyName)
{
    if(!isset($myRow[$keyName])){
        return "-";
    }
    else{
        return $myRow[$keyName];
    }
}

function GetNumber($formValue){
    if (is_numeric($formValue)){
        return $formValue + 0;        
    }
    else {
        // Let the number be 0 if the string is not a number
        return 0;
    }
}

function GetString($formValue){
    return addslashes($formValue) ;  
}

function GetStringFromArray($formArray, $keyName){
    if(isset($formArray[ $keyName])){
      return GetString($formArray[$keyName]);  
    }
    else{
      return "--";
    }
}

function GetNumberFromArray($formArray, $keyName){
    if(isset($formArray[ $keyName])){
      return GetNumber($formArray[$keyName]);  
    }
    else{
        return 0;
    }
}

function SqlDataReplace($placeHolder, $value, $sqlStatment){
    $valueLower = strtolower($value);

    $findme = strtolower("insert ");
    $pos = strpos($valueLower, $findme);
    if ($pos === false) 
    {
        $findme = strtolower("update ");
        $pos = strpos($valueLower, $findme);
        if ($pos === false) 
        {
            $findme = strtolower("select ");
            $pos = strpos($valueLower, $findme);
            if ($pos === false) 
            {
                $findme = strtolower("delete ");
                $pos = strpos($valueLower, $findme);
                if ($pos === false) 
                {
                    $findme = strtolower(" or ");
                    $pos = strpos($valueLower, $findme);
                    if ($pos === false) 
                    {
                        $findme = strtolower(" where ");
                        $pos = strpos($valueLower, $findme);
                        if ($pos === false) 
                        {
                            
                        }
                    }
                }
            }
        }
    }


    if ($pos === false) 
    {
        return str_replace($placeHolder, $value, $sqlStatment);        
    }
    else
    {
        //TODO: log the sql-injection attempt
        return str_replace($placeHolder, 'sql-injection', $sqlStatment);
    }
}

function IsValueToBeEnclosedInQuotes($Value_Type){
    if($Value_Type == INTEGER){
        return false;
    }
    return true;
}

function IsCommaTerminatorRequired($CurrentColumnCount, $MaxColumnCount){
    if($CurrentColumnCount == $MaxColumnCount){
        return false;
    }
    return true;
}

function UpdatePlaceHoldersWithinReadDataSQL($sqlStatment, $ColumnsToBeReturned){
    $sqlStatmentNew = $sqlStatment;

    $post_data = file_get_contents('php://input'); // Get Raw Posted Data
    $json = json_decode($post_data, true); // Decode it

    foreach($ColumnsToBeReturned as $key => $key_value_type) {
        $findme = ":".$key.":";
        $pos = strpos($sqlStatmentNew, $findme);
        if (!($pos === false)) {
            if($key_value_type == INTEGER){
                $sqlStatmentNew =  SqlDataReplace($findme, GetNumberFromArray($json, $key), $sqlStatmentNew);
            }
            if($key_value_type == STRING){
                $sqlStatmentNew =  SqlDataReplace($findme, GetStringFromArray($json, $key), $sqlStatmentNew);
            }           
        }
    }          

    return $sqlStatmentNew;
}

function PrintDataQueryAsJSONTest($sqlStatmentNew){
        //open the database
        $db = new PDO(GetSQLite());
        //$result = $db->query($sqlStatmentNew);
        //print $result;
        //$result = $stmt->fetchALL(PDO::FETCH_CLASS);
        //print_r ($result);

        foreach ($db->query($sqlStatmentNew) as $row) {
            print_r ($row);
            //print $row['name'] . "\t";
            //print $row['color'] . "\t";
            // $row['calories'] . "\n";
        }


        $db = NULL;



}

function ConditionalPrint($stringToBePrinted, $AllowedToPrint){
    if ($AllowedToPrint) {
        print $stringToBePrinted;
    }
}

function PrintDataQueryAsJSON($sqlStatment, $ColumnsToBeReturned){
    $sqlStatmentNew = UpdatePlaceHoldersWithinReadDataSQL($sqlStatment, $ColumnsToBeReturned);  
    try
    {       
        //open the database
        $db = new PDO(GetSQLite());
        //$db->setAttribute("PDO::ATTR_ERRMODE", PDO::ERRMODE_EXCEPTION);
        $result = $db->query($sqlStatmentNew);

        $CurrentRowCount = 0;
        $MaxColumnCount = count($ColumnsToBeReturned);  //Can cause warning if array is null

        $OpenBracesPrinted = false;
        if(isset($result) ) 
        {
           
            foreach($result as $row)
            {

                ConditionalPrint('[', ($OpenBracesPrinted==false));   //print only if there are records
                $OpenBracesPrinted = true;

                print GetComma($CurrentRowCount > 0);
                print '{';
                    $CurrentColumnCount = 0;
                    foreach($ColumnsToBeReturned as $key => $key_value_type) {
                        $CurrentColumnCount = $CurrentColumnCount + 1;
                        print GetJSONKeyValueFromRow($row, $key, IsValueToBeEnclosedInQuotes($key_value_type), IsCommaTerminatorRequired($CurrentColumnCount, $MaxColumnCount));
                    }                    
                print '}';

                $CurrentRowCount  = $CurrentRowCount  + 1;
            }

            ConditionalPrint(']', ($OpenBracesPrinted==true) ) ;            
        }
        else
        {
            print '[';
                print '{';
                    print GetJSONKeyValue("sqlStatment", $sqlStatmentNew, const_value_enclosed_in_quotes, const_comma_required);
                    print '"isset_result": "'.isset($result) .'",';          
                    print '"Did not execute" : "Check db-helper"';          
                print '}';
            print ']';
        }


        $db = NULL;

        return $CurrentRowCount;
    }
    catch(Exception $e)
    {
        print '[';
            print '{';
                print GetJSONKeyValue("sqlStatment", $sqlStatmentNew, const_value_enclosed_in_quotes, const_comma_required);
                print '{"Exception" : "'.$sqlStatmentNew."'  '".$e->getMessage().'"}';          
            print '}';
        print ']';
    }
}

function GetFirstColumnFirstRow($sqlStatment, $ColumnsToBeReturned){
    $sqlStatmentNew = UpdatePlaceHoldersWithinReadDataSQL($sqlStatment, $ColumnsToBeReturned);  
    try
    {       
        //open the database
        $db = new PDO(GetSQLite());
        $result = $db->query($sqlStatmentNew);

        if(isset($result) ) 
        {
            foreach($result as $row)
            {
                    foreach($ColumnsToBeReturned as $key => $key_value_type) {
                        return (GetKeyValueFromRow($row, $key)) ;
                    }                    
            }   
        }
        else
        {
            return "Did not execute";
        }

        $db = NULL;
    }
    catch(Exception $e)
    {
        return $e->getMessage();
    }
}
  
function GetFirstRowAsArray($sqlStatment, $ColumnsToBeReturned){
    $sqlStatmentNew = UpdatePlaceHoldersWithinReadDataSQL($sqlStatment, $ColumnsToBeReturned);  
    try
    {       
        //open the database
        $db = new PDO(GetSQLite());
        $result = $db->query($sqlStatmentNew);

        if(isset($result) ) 
        {
            foreach($result as $row)
            {
                    foreach($ColumnsToBeReturned as $key => $key_value_type) {
                        $rowdata[$key] =  (GetKeyValueFromRow($row, $key));
                    }   
                    return  $rowdata;                
            }   
        }
        else
        {
            $rowdata["Exception"] = "Did not execute";
            return  $rowdata;     
        }

        $db = NULL;
    }
    catch(Exception $e)
    {
        $rowdata["Exception"] = $e->getMessage();
        return  $rowdata;
    }
}

function GetFirstColumnAsCSVString($sqlStatment, $ColumnsToBeReturned){
    $sqlStatmentNew = UpdatePlaceHoldersWithinReadDataSQL($sqlStatment, $ColumnsToBeReturned);  
    try
    {       
        //open the database
        $db = new PDO(GetSQLite());
        $result = $db->query($sqlStatmentNew);

        $CSV ="";
        if(isset($result) ) 
        {
            $CurrentRowCount = 0;
            foreach($result as $row)
            {
                $CurrentColumnCount = 0;
                $CurrentRowCount = $CurrentRowCount + 1;
                foreach($ColumnsToBeReturned as $key => $key_value_type) {
                    $CurrentColumnCount = $CurrentColumnCount + 1;
                    if($CurrentColumnCount==1){
                        $CSV = $CSV.GetComma($CurrentRowCount > 1).(GetKeyValueFromRow($row, $key));                       
                    }
                }                    
            }   
            return $CSV;
        }
        else
        {
            return "Did not execute";
        }

        $db = NULL;
    }
    catch(Exception $e)
    {
        return $e->getMessage();
    }
}


function GetDataAsArray($sqlStatment, $ColumnsToBeReturned){
    $sqlStatmentNew = UpdatePlaceHoldersWithinReadDataSQL($sqlStatment, $ColumnsToBeReturned);  
    try
    {       
        //open the database
        $db = new PDO(GetSQLite());
        $result = $db->query($sqlStatmentNew);

        if(isset($result) ) 
        {
            $CurrentRowCount = 0;
            foreach($result as $row)
            {
                foreach($ColumnsToBeReturned as $key => $key_value_type) {
                    $rowdata[$key] =  (GetKeyValueFromRow($row, $key));
                }   
                $multiRow[$CurrentRowCount] = $rowdata;
                $CurrentRowCount = $CurrentRowCount + 1;             
            }   
            return  $multiRow;  
        }
        else
        {
            $rowdata["Exception"] = "Did not execute";
            $multiRow[0] = $rowdata;
            return  $multiRow;     
        }

        $db = NULL;
    }
    catch(Exception $e)
    {
        $rowdata["Exception"] = $e->getMessage();
        $multiRow[0] = $rowdata;
        return  $multiRow;     
    }
}

function GetRanNegPosNr(){
    // we do not want zero. can be negative or postive number
    return (rand(0,1)*2-1)*rand(1, 10);
}


function ExecuteDMLInsertUpdateDelete($InsertStatement, $UpdateStatement, $DeleteStatement, $ColumnsToBeReturned, $primaryKeyName){

    $sqlStatmentNew = UpdatePlaceHoldersWithinReadDataSQLForDML($InsertStatement, $UpdateStatement, $DeleteStatement, $ColumnsToBeReturned, $primaryKeyName); 
    $boolResult = false; 
    try
    {       
        //open the database
        $db = new PDO(GetSQLite());
        $boolResult = $db->exec($sqlStatmentNew);        
        if ($boolResult === FALSE) {
            print '[';
                print '{';
                    print GetJSONKeyValue("DMLFailed", $sqlStatmentNew, const_value_enclosed_in_quotes, const_comma_not_required);             
                print '}';
            print ']';            
        }
        $db = NULL;         
    }
    catch(Exception $e)
    {
        print '[';
            print '{';
                print GetJSONKeyValue("DMLFailed", $sqlStatmentNew, const_value_enclosed_in_quotes, const_comma_required);
                print '{"Exception" :"'.$e->getMessage().'"}';          
            print '}';
        print ']';
    }
    return $boolResult;
}

function UpdatePlaceHoldersWithinReadDataSQLForDML($InsertStatement, $UpdateStatement, $DeleteStatement, $ColumnsToBeReturned, $primaryKeyName){

    $post_data = file_get_contents('php://input'); // Get Raw Posted Data
    $json = json_decode($post_data, true); // Decode it

    $actiondelete = GetNumberFromArray($json, "actiondelete");
    $primaryKeyvalue = GetNumberFromArray($json, $primaryKeyName);

    if($actiondelete == 1){
        $sqlStatment = $DeleteStatement; 
    }elseif($primaryKeyvalue > 0){
        $sqlStatment = $UpdateStatement; 
    }else{
        $sqlStatment = $InsertStatement;      
    }
    $sqlStatmentNew = $sqlStatment;

    foreach($ColumnsToBeReturned as $key => $key_value_type) {
        $findme = ":".$key.":";
        $pos = strpos($sqlStatmentNew, $findme);
        if (!($pos === false)) {
            if($key_value_type == INTEGER){
                $sqlStatmentNew =  SqlDataReplace($findme, GetNumberFromArray($json, $key), $sqlStatmentNew);
            }
            if($key_value_type == STRING){
                $sqlStatmentNew =  SqlDataReplace($findme, GetStringFromArray($json, $key), $sqlStatmentNew);
            }           
        }
    }          

    return $sqlStatmentNew;
}

?>