<?php 
    include "pJSON\payload-keys.php"
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body onload="start();">
    <h1>Hello, world!</h1>

    <div id="displaydataJSON">
        {
            "url":"/pJSON/getdata.php",
            "payload":{
                "action":"PreviousQuestion"
            }
        }
    </div>

    <div id="displaydataHTML">
        {
            "url":"/pJSON/getdataHTML.php",
            "payload":{
                "action":"PreviousQuestion"
            }
        }
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script>

        // Example POST method implementation:
        async function postDataGetJSON(url = '', data = {}) {
          // Default options are marked with *
          const response = await fetch(url, {
            method: 'POST', // *GET, POST, PUT, DELETE, etc.
            mode: 'cors', // no-cors, *cors, same-origin
            cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
            credentials: 'same-origin', // include, *same-origin, omit
            headers: {
              'Content-Type': 'application/json'
              // 'Content-Type': 'application/x-www-form-urlencoded',
            },
            redirect: 'follow', // manual, *follow, error
            referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
            body: JSON.stringify(data) // body data type must match "Content-Type" header
          });
          return response.json(); // parses JSON response into native JavaScript objects
        }
                
        // Example POST method implementation:
        async function postDataGetHTML(url = '', data = {}) {
          // Default options are marked with *
          const response = await fetch(url, {
            method: 'POST', // *GET, POST, PUT, DELETE, etc.
            mode: 'cors', // no-cors, *cors, same-origin
            cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
            credentials: 'same-origin', // include, *same-origin, omit
            headers: {
              'Content-Type': 'application/json'
              // 'Content-Type': 'application/x-www-form-urlencoded',
            },
            redirect: 'follow', // manual, *follow, error
            referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
            body: JSON.stringify(data) // body data type must match "Content-Type" header
          });
          return response.text(); // parses JSON response into native JavaScript objects
        }

        //document.getElementById("DisplayPreviousQuestion").innerText
        function start(){
            DivInitJSON("displaydataJSON");
            DivInitHTML("displaydataHTML");
            /*
            postData('/pJSON/getdata.php', {element01: 4  })
            .then(data => {
                //console.log(data); // JSON data parsed by `data.json()` call
                document.getElementById("displaydata").innerText= JSON.stringify(data);
            });
            */
        }

        function FillDivJSON(divName, url, jsonPostData){
            postDataGetJSON(url, jsonPostData)
            .then(data => {
                //console.log(data); // JSON data parsed by `data.json()` call
                document.getElementById(divName).innerText= JSON.stringify(data);
            }).catch(function (err) {
                // There was an error
                console.warn('Something went wrong.', err);
                document.getElementById(divName).innerText='fourth.php: line 104: Something went wrong. Check console.';
            });
        }
        
        function FillDivHTML(divName, url, jsonPostData){
            postDataGetHTML(url, jsonPostData)
            .then(data => {
                //console.log(data); // JSON data parsed by `data.json()` call
                document.getElementById(divName).innerHTML= data;
            }).catch(function (err) {
                // There was an error
                console.warn('Something went wrong.', err);
                document.getElementById(divName).innerText='fourth.php: line 116: Something went wrong. Check console.';
            });
        }

        function DivInitJSON(divName){
            let divSourceObj =  JSON.parse(document.getElementById(divName).innerText);
            FillDivJSON(divName, divSourceObj.url, divSourceObj.payload);
        }
        
        function DivInitHTML(divName){
            let divSourceObj =  JSON.parse(document.getElementById(divName).innerText);
            FillDivHTML(divName, divSourceObj.url, divSourceObj.payload);
        }
        
        </script>

</body>
</html>
 