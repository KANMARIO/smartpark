<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "smartpark";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn-> connect_error){
        die("koneksi failed: " . $conn->connect_error);
    }
    echo "";
    ?>

    