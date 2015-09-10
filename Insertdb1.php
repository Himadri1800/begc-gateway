<?php  

/*=========================================
mysql code to add the data to database
CREATE TABLE tmpapplicant (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
txnid VARCHAR(50) NOT NULL,
applicant_name VARCHAR(30) NOT NULL,
username  VARCHAR(30) NOT NULL,
password VARCHAR(30) NOT NULL,
gname VARCHAR(30) NOT NULL,
presentadd VARCHAR(250) NOT NULL,
presentstate VARCHAR(100) NOT NULL,
permanentadd VARCHAR(250) NOT NULL,
permanentstate VARCHAR(100) NOT NULL,
postapplying VARCHAR(50) NOT NULL,
imagelocation VARCHAR(250) UNIQUE,
email VARCHAR(100) UNIQUE,
mobile VARCHAR(10) NOT NULL,
dob date NOT NULL,
gender VARCHAR(25) NOT NULL,
cast VARCHAR(25) NOT NULL,
nationality VARCHAR(25) NOT NULL,
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
=================================================*/

/*CREATE TABLE tmpqualification (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
txnid VARCHAR(50) NOT NULL,
exampass VARCHAR(50) NOT NULL,
year VARCHAR(50) NOT NULL,
board VARCHAR(50) NOT NULL,
marks VARCHAR(10) NOT NULL,
percentage VARCHAR(10)
)*/



// Create connection
$conn = new mysqli($servername, $username, $dbpassword, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$dob = date('Y-m-d', strtotime($dob));

$sql = "INSERT INTO tmpapplicant (txnid,applicant_name, username,password, gname, presentadd, presentstate, permanentadd,permanentstate , postapplying, imagelocation, email,mobile,dob,gender,cast,nationality)
VALUES ('$txnid','$firstname', '$name', '$passwordhash', '$gname', '$present_ad', '$permanentslist' ,'$permanent_ad', '$presentslist', '$postapplying', '$imagelocation','$email','$phone','$dob','$gender','$cast','$nationality');";


$sql .="INSERT INTO tmpqualification(txnid,exampass,year,board,marks,percentage)
VALUES ('$txnid','$exam1','$year1','$board1','$marks1','$percent1');";

$sql .="INSERT INTO tmpqualification(txnid,exampass,year,board,marks,percentage)
VALUES ('$txnid','$exam2','$year2','$board2','$marks2','$percent2');";

$sql .="INSERT INTO tmpqualification(txnid,exampass,year,board,marks,percentage)
VALUES ('$txnid','$exam3','$year3','$board3','$marks3','$percent3');";

if ($conn->multi_query($sql) === TRUE) {
    //echo "New records created successfully";
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
    echo "There is an error with the Database Please contact begc.co.in";
    exit;
}


$conn->close();
//$action = $PAYU_BASE_URL . '/_payment';

/*mysql code ends here!!*/


?>