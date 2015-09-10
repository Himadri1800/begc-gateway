<?php

/*=========================================
mysql code to add the data to database
CREATE TABLE applicant (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
txnid VARCHAR(50) NOT NULL,
applicant_name VARCHAR(30) NOT NULL,
gname VARCHAR(30) NOT NULL,
presentadd VARCHAR(250) NOT NULL,
permanentadd VARCHAR(250) NOT NULL,
email VARCHAR(100) NOT NULL,
mobile int(10) NOT NULL,
dob date NOT NULL,
gender VARCHAR(25) NOT NULL,
cast VARCHAR(25) NOT NULL,
nationality VARCHAR(25) NOT NULL,
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
=================================================*/

/*the configuration file set to define the 
mailserver, database and necessary content*/
@require('config.php');


$txnid=stripslashes($txnid);
// Create connection
$conn = new mysqli($servername, $username, $dbpassword, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "INSERT INTO applicant (select * from tmpapplicant where txnid = '$txnid');";

if ($conn->multi_query($sql) === TRUE) {
    //echo "New records created successfully";
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
    echo "There is an error with the Database Please contact begc.co.in";
    exit;
}

$sql = "INSERT INTO qualification (select * from tmpqualification where txnid = '$txnid');";

if ($conn->multi_query($sql) === TRUE) {
    //echo "New records created successfully";
} else {
    echo "There is an error with the Database Please contact begc.co.in";
    exit;
}
$conn->close();


?>