<?php

$HOSTNAME = 'localhost';
$USERNAME = 'root'; 
$PASSWORD = ''; 
$DATABASE = 'industry';

$con = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);


if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";

//mysqli_close($con);

?>
