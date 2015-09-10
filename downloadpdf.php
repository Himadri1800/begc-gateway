<?php

//include 'Insertdb2.php';
include("himpdf/mpdf.php");
require  'medoo.php';
require 'config.php';

print_r($_GET);

$txnid = $_GET["txnid"];
$txnid = stripcslashes($txnid);


          
          $database = new medoo([
          'database_type' => 'mysql',
          'database_name' => $dbname,
          'server' => $servername,
          'username' => $username,
          'password' => $dbpassword
          ]);

          $datas = $database->select("applicant", "*", ["txnid"=> $txnid]);
          //print_r($datas);
          $message = file_get_contents('confirmaitonmail.php');

          $message = str_replace("applicant_uname", $datas[0]['username'] ,$message);
          $message = str_replace("applicant_name", $datas[0]['applicant_name'],$message);
          $message = str_replace("applicant_email", $datas[0]['email'],$message);
          $message = str_replace("applicant_gname", $datas[0]['gname'],$message);
          $message = str_replace("applicant_presentadd", $datas[0]['presentadd'],$message);
          $message = str_replace("applicant_permanentadd", $datas[0]['permanentadd'],$message);
          $message = str_replace("applicant_phone", $datas[0]['mobile'],$message);
          $message = str_replace("applicant_dob", $datas[0]['dob'],$message);
          $message = str_replace("applicant_gender", $datas[0]['gender'],$message);
          $message = str_replace("applicant_cast", $datas[0]['cast'],$message);
          $message = str_replace("applicant_nationality", $datas[0]['nationality'],$message);
          $message = str_replace("applicant_postapplying", $datas[0]['postapplying'],$message);

          if($datas[0]['cast']==gen){
               $amount="200";
          }else{
               $amount="100";
          }

          $message = str_replace("applicant_amount", $amount, $message);
		  $message = str_replace("applicant_txnid", $datas[0]['txnid'],$message);

		  $qual = $database->select("tmpqualification", "*", ["txnid"=> $txnid]);
		  print_r($qual);
		  $message = str_replace("exam_1", $qual[0]['exampass'],$message);
		  $message = str_replace("year_1", $qual[0]['year'],$message);
		  $message = str_replace("board_1", $qual[0]['board'],$message);
		  $message = str_replace("marks_1", $qual[0]['marks'],$message);
		  $message = str_replace("percent_1", $qual[0]['percentage'],$message);

		  $message = str_replace("exam_2", $qual[1]['exampass'],$message);
		  $message = str_replace("year_2", $qual[1]['year'],$message);
		  $message = str_replace("board_2", $qual[1]['board'],$message);
		  $message = str_replace("marks_2", $qual[1]['marks'],$message);
		  $message = str_replace("percent_2", $qual[1]['percentage'],$message);

		  $message = str_replace("exam_3", $qual[2]['exampass'],$message);
		  $message = str_replace("year_3", $qual[2]['year'],$message);
		  $message = str_replace("board_3", $qual[2]['board'],$message);
		  $message = str_replace("marks_3", $qual[2]['marks'],$message);
		  $message = str_replace("percent_3", $qual[2]['percentage'],$message);



		$mpdf=new mPDF('c'); 
		  $mpdf->WriteHTML($message);
		  $mpdf->Output();
		  exit;


?>