<?php 
$hostName = "localhost";
$dbAdmin = "root";
$dbPassword = "";
$dbName = "stamarta";

$connection = new mysqli($hostName, $dbAdmin, $dbPassword, $dbName);

if ($connection->connect_error) {
    die("Something went wrong; " . $connection->connect_error);
} else {
    echo "Connected to database successfully.";
}

?>