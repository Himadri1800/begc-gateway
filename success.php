<?php
error_reporting(E_ERROR | E_PARSE);
require  'medoo.php';

$status=$_POST["status"];
$firstname=$_POST["firstname"];
$amount=$_POST["amount"];
$txnid=$_POST["txnid"];
$posted_hash=$_POST["hash"];
$key=$_POST["key"];
$productinfo=$_POST["productinfo"];
$email=$_POST["email"];
$salt="laUBrdjX";

If (isset($_POST["additionalCharges"])) {
       $additionalCharges=$_POST["additionalCharges"];
        $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        
                  }
	else {	  

        $retHashSeq = $salt.'|'.$status.'|||||||||||'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;

         }
		 $hash = hash("sha512", $retHashSeq);

     /*?>

<!-- Form designe -->



<!-- Design ends here -->



     <?php*/
		 
       if ($hash != $posted_hash) {
	       echo "Sorry Transaction Failed. Please try again";
		   }
	   else {


          include 'Insertdb2.php';

          
          $database = new medoo([
          'database_type' => 'mysql',
          'database_name' => $dbname,
          'server' => $servername,
          'username' => $username,
          'password' => $dbpassword
          ]);

          $datas = $database->select("tmpapplicant", "*", ["txnid"=> $txnid]);

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
          $message = str_replace("applicant_amount", $amount, $message);
		      $message = str_replace("applicant_txnid", $datas[0]['txnid'],$message);

          $qual = $database->select("tmpqualification", "*", ["txnid"=> $txnid]);
        //print_r($qual);
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


          $to = $datas[0]['email'];
          $subject = 'confirmation E-mail | begc.co.in';
          $headers = 'From: confirmation@begc.in' . "\r\n" .
          'Reply-To: confirmation@begc.in' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();
          $headers.="MIME-Version: 1.0 \r\n";
          $headers.="Content-type: text/html; charset=\"UTF-8\" \r\n";
          $headers.= "Bcc: confirmation@begc.in\r\n";
         
          if(mail($to, $subject, $message, $headers)) {
             echo 'Email sent successfully!';
          } else {
          die('Failure: Email was not sent!');
          }   
          
          echo $message;
          echo '<form method="get" action="downloadpdf.php"><input type="hidden" name="txnid" value="'. $txnid . '"/><button type="submit">Download pdf</button></form>';

       	  /*echo "<h3>The Registration ". $status .".</h3>";
          echo "<h4>Your Transaction ID for this transaction is ".$txnid.".</h4>";
          echo "<h4>We have received a payment of Rs. " . $amount . "/- .</h4>";
          */
             

          
		   }         
?>	