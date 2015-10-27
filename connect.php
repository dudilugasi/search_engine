<?php

define("SERVER_NAME", "localhost");
define("USER_NAME", "dudi");
define("PASS", "dudi");
define("DB_NAME", "search_engine");

$servername = SERVER_NAME;
$username = USER_NAME;
$password = PASS;
$dbname = DB_NAME;

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


