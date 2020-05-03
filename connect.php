<?php
$servername = "localhost:3306";
$username = "root";
$password = ""; #enter password

try {
    $conn = new PDO("mysql:host=$servername;dbname=qversityr1", $username, $password);
    #set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); #for error reporting and exceptions
    echo "Connected successfully";
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }

?> 