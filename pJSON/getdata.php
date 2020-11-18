
<?php 
    function getReferer(){
        try
        {
            return $_SERVER['REQUEST_URI'] ;
        }catch (Exception $e2) {
            return 'Caught exception: ';
        }        
    }

?>

{
    "server-response": "hello-world",
    "requestor" :"<?php echo $_SERVER['REQUEST_URI'];  ?>",
    "refferer" : "<?php echo getReferer(); ?>"
}