<?php
/*mailserver, database and necessary content*/
error_reporting(E_ERROR | E_PARSE);
@require('config.php');


if ($_POST) {

	if($_POST["usercast"]=='gen') {
			$amount=220;
		}else{
			$amount=120;
		}

   
   $firstname = $_POST["firstname"];
   $email  = $_POST["email"];
   $name = $_POST["name"];
   $phone = $_POST["phone"];
   $gname   = $_POST["gname"];
   $present_ad   = $_POST["present_ad"];
   $permanent_ad   = $_POST["permanent_ad"];
   $imagelocation = $_POST["imagelocation"];
   $passwordhash = $_POST["passwordhash"];
   $dob = $_POST["dob"];
   $gender = $_POST["gender"];
   $cast = $_POST["usercast"];
   $nationality = $_POST["nationality"];
   $securitycode   = $_POST["securitycode"];
   $date = date("d.m.Y | H:i");
   $ip = $_SERVER['REMOTE_ADDR']; 
   $UserAgent = $_SERVER["HTTP_USER_AGENT"];
   $postapplying =$_POST["postapplying"];
   $presentslist =$_POST["presentslist"];
   $permanentslist =$_POST["permanentslist"];

   $exam1 = $_POST["exam1"];
   $year1 = $_POST["year1"];
	$board1 = $_POST["board1"];
	$marks1 = $_POST["marks1"];
	$percent1 = $_POST["percent1"];

	$exam2 = $_POST["exam2"];
	$year2 = $_POST["year2"];
	$board2 = $_POST["board2"];
	$marks2 = $_POST["marks2"];
	$percent2 = $_POST["percent2"];

	$exam3 = $_POST["exam3"];
	$year3 = $_POST["year3"];
	$board3 = $_POST["board3"];
	$marks3 = $_POST["marks3"];
	$percent3 = $_POST["percent3"];

}

$MERCHANT_ID = "5259268";
$MERCHANT_KEY = "1zQf2t";

// Merchant Salt as provided by Payu
$SALT = "laUBrdjX";

// End point - change to https://secure.payu.in for LIVE mode
//$PAYU_BASE_URL = "https://test.payu.in";

$PAYU_BASE_URL = "https://secure.payu.in";

$action = '';

$posted = array();
if(!empty($_POST)) {
    //print_r($_POST);
  foreach($_POST as $key => $value) {    
    $posted[$key] = $value; 
	
  }
}

$formError = 0;

if(empty($posted['txnid'])) {
  // Generate random transaction id
  //$txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
  $txnid = substr(hash('sha512', mt_rand() . microtime()), 0, 20);
} else {
  $txnid = $posted['txnid'];
}
$hash = '';
// Hash Sequence
$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
if(empty($posted['hash']) && sizeof($posted) > 0) {
  if(
          empty($posted['key'])
          || empty($posted['txnid'])
          || empty($posted['amount'])
          || empty($posted['firstname'])
          || empty($posted['email'])
          || empty($posted['phone'])
          || empty($posted['productinfo'])
          || empty($posted['surl'])
          || empty($posted['furl'])
		  || empty($posted['service_provider'])
  ) {
    $formError = 1;
  } else {
    //$posted['productinfo'] = json_encode(json_decode('[{"name":"tutionfee","description":"","value":"500","isRequired":"false"},{"name":"developmentfee","description":"monthly tution fee","value":"1500","isRequired":"false"}]'));
	$hashVarsSeq = explode('|', $hashSequence);
    $hash_string = '';	
	foreach($hashVarsSeq as $hash_var) {
      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
      $hash_string .= '|';
    }

    $hash_string .= $SALT;


    $hash = strtolower(hash('sha512', $hash_string));
    include 'Insertdb1.php';
    $action = $PAYU_BASE_URL . '/_payment';
  }
} elseif(!empty($posted['hash'])) {
  $hash = $posted['hash'];
  include 'Insertdb1.php';
  $action = $PAYU_BASE_URL . '/_payment';
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de-DE" lang="de-DE">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="language"       content="us"/>
<meta name="description"      content="himadrimandal.com"/>
<meta name="revisit"          content="After 7 days"/>
<meta name="robots"           content="INDEX,FOLLOW"/>
<meta http-equiv="Content-Style-Type" content="text/css" />   
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<title>Test Payment</title>
<link href="style-contact-form.css" rel="stylesheet" type="text/css" />

</head>
<script type='text/javascript'>
  
  var hash = '<?php echo $hash ?>';
    function submitPayuForm() {
      if(hash == '') {
        return;
      }
      var payuForm = document.forms.payuForm;
      payuForm.submit();
    }


</script>

<body id="Kontaktformularseite" onload="submitPayuForm()">

<div class="kontaktformular">
<center><img style="float: auto; text-align: center" src="font/logo.png" alt="Bio-Chemic Education Grant Commission, Govt. of India"/></center>
<center>Bakshi Lane, Bowbazar, P.O: Krishnanagar, Dist.: Nadia, W.B -741101</center>
	<form action="<?php echo $action; ?>" method="post" name="payuForm">


      <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
      <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
      <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
      <input type="hidden" name="service_provider" value="payu_paisa" size="64" />
      <input type="hidden" name="productinfo" value="Registration form" size="64" />
      <input type="hidden" name="surl" value="<?php echo $surl; ?>"/>
      <input type="hidden" name="furl" value="<?php echo $furl; ?>"/>
      <input type="hidden" name="name" value="<?php echo $_POST[name];?>"/>
      

      <input type="hidden" name="firstname" value="<?php echo $_POST[firstname];?>" />
      <input type="hidden" name="name" value="<?php echo $_POST[name];?>" />
      <input type="hidden" name="email" value="<?php echo $_POST[email];?>" />
      <input type="hidden" name="gname" value="<?php echo $_POST[gname];?>" />
      <input type="hidden" name="present_ad" value="<?php echo $_POST[present_ad];?>" />
      <input type="hidden" name="permanent_ad" value="<?php echo $_POST[permanent_ad];?>" />
      <input type="hidden" name="passwordhash" value="<?php echo $_POST[passwordhash];?>" />
      <input type="hidden" name="dob" value="<?php echo $_POST[dob];?>" />
      <input type="hidden" name="imagelocation" value="<?php echo $_POST[imagelocation]; ?>" />
      <input type="hidden" name="gender" value="<?php echo $_POST[gender];?>" />
      <input type="hidden" name="usercast" value="<?php echo $_POST[usercast];?>" />
      <input type="hidden" name="nationality" value="<?php echo $_POST[nationality];?>" />
      <input type="hidden" name="date" value="<?php echo $_POST[date];?>" />
      <input type="hidden" name="postapplying" value="<?php echo $_POST[postapplying];?>" />
      <input type="hidden" name="presentslist" value="<?php echo $_POST[presentslist];?>" />
      <input type="hidden" name="permanentslist" value="<?php echo $_POST[permanentslist];?>" />

      <input type="hidden" name="exam1" value="<?php echo $_POST[exam1]; ?>" />
      <input type="hidden" name="year1" value="<?php echo $_POST[year1]; ?>" />
      <input type="hidden" name="board1" value="<?php echo $_POST[board1]; ?>" />
      <input type="hidden" name="marks1" value="<?php echo $_POST[marks1]; ?>" />
      <input type="hidden" name="percent1" value="<?php echo $_POST[percent1]; ?>" />

      <input type="hidden" name="exam2" value="<?php echo $_POST[exam2]; ?>" />
      <input type="hidden" name="year2" value="<?php echo $_POST[year2]; ?>" />
      <input type="hidden" name="board2" value="<?php echo $_POST[board2]; ?>" />
      <input type="hidden" name="marks2" value="<?php echo $_POST[marks2]; ?>" />
      <input type="hidden" name="percent2" value="<?php echo $_POST[percent2]; ?>" />

      <input type="hidden" name="exam3" value="<?php echo $_POST[exam3]; ?>" />
      <input type="hidden" name="year3" value="<?php echo $_POST[year3]; ?>" />
      <input type="hidden" name="board3" value="<?php echo $_POST[board3]; ?>" />
      <input type="hidden" name="marks3" value="<?php echo $_POST[marks3]; ?>" />
      <input type="hidden" name="percent3" value="<?php echo $_POST[percent3]; ?>" />


      <input type="hidden" name="phone" value="<?php echo $_POST[phone];?>" />
      
      <input type="hidden" name="amount" value="<?php echo (empty($posted['amount'])) ? $amount : $posted['amount'] ?>" />

   <!-- firstname = $_POST["firstname"];
   $email  = $_POST["email"];
   $name = $_POST["name"];
   $phone = $_POST["phone"];
   $gname   = $_POST["gname"];
   $present_ad   = $_POST["present_ad"];
   $permanent_ad   = $_POST["permanent_ad"];
   $imagelocation = $_POST["imagelocation"];
   $passwrodhash = $_POST['passwrodhash'];
   $dob = $_POST["dob"];
   $gender = $_POST["gender"];
   $cast = $_POST["usercast"];
   $nationality = $_POST["nationality"];
   $securitycode   = $_POST["securitycode"];
   $date = date("d.m.Y | H:i");
   $ip = $_SERVER['REMOTE_ADDR']; 
   $UserAgent = $_SERVER["HTTP_USER_AGENT"];
   $postapplying =$_POST["postapplying"]; -->
  	


  	<p><input style="width:0px; height:0px; visibility:hidden;" type="hidden" name="action" value="smail" /></p>
	<p><input style="width:0px; height:0px; visibility:hidden;" type="hidden" name="content" value="formular"/></p>

      <fieldset class="kontaktdaten">
   	<legend>Applicant data</legend>
  		<table>

  		<tr>
		     <td class="label"><label>User Name:</span></label></td>
      		<td class="label"><?php echo $_POST[name];?></td>
      	</tr>

    	<tr>
		     <td class="label"><label>Name: </label></td>
      		<td class="label"><?php echo $_POST[firstname]; ?></td>
      	</tr>

      	<tr>
		     <td class="label"><label>Father`s /Husband /Guardian Name: </label></td>
      		<td class="label"><?php echo $_POST[gname]; ?></td>
      	</tr>

      	<tr>
		     <td class="label"><label>Present Address: </label></td>
      		<td class="label"><?php echo $_POST[present_ad]; ?></td>
      	</tr>

      	<tr>
		     <td class="label"><label>Permanent Address: </label></td>
      		<td class="label"><?php echo $_POST[permanent_ad]; ?></td>
      	</tr>
      	
      	<tr>
		     <td class="label"><label>Birth date: </label></td>
      		<td class="label"><?php echo $_POST[dob]; ?></td>
      	</tr>

      	<tr>
		     <td class="label"><label>Gender: </label></td>
      		<td class="label"><?php echo $_POST[gender] ; ?></td>
      	</tr>

      	<tr>
		     <td class="label"><label>Email Address: </label></td>
      		<td class="label"><?php echo $_POST[email] ; ?></td>
      	</tr>


      	<tr>
		     <td class="label"><label>Phone Number: </span></label></td>
      		<td class="label"><?php echo $_POST[phone] ; ?></td>
      	</tr>

      	
      	<tr>
		     <td class="label"><label>Nationality: </span></label></td>
      		<td class="label"><?php echo $_POST[nationality] ; ?></td>
      	</tr>

      	<tr>
		     <td class="label"><label>Cast: </span></label></td>
      		<td class="label"><?php echo $_POST[usercast] ; ?></td>
      	</tr>

      	<tr>
		     <td class="label"><label>Amount: </label></td>
      		<td class="label">Rs. <?php echo (empty($posted['amount'])) ? $amount : $posted['amount'] ?>/- </td>
      	</tr>
		<tr><h5>* The  amount include Rs. 20/- as Service charges</h5></tr>

 <?php if(!$hash) { ?>
            <td colspan="4"><input type="submit" value="Submit" /></td>
          <?php } ?>
        </table>
      </fieldset>
      <div style="font-size:11px; text-align:center"><!-- It´s not allowed to remove the copyright notice! --><strong>Copyright</strong>: &copy; 2015 - 2020 <a href="http://www.begc.co.in/" title="Contact Form"> Bio-chemic Education Grant Commission, Govt of India. </a> </div>
    </div>
  </body>
  </html>
     	
      	