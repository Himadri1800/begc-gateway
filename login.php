<?php
error_reporting(E_ERROR | E_PARSE);
session_destroy();
session_start();


header('Content-type: text/html; charset=utf-8');
  $script_root = substr(__FILE__, 0,
                        strrpos(__FILE__,
                                DIRECTORY_SEPARATOR)
                       ).DIRECTORY_SEPARATOR;

@require  'medoo.php';
@require('config.php');


if ($_POST['delete']) {
	unset($_POST);
}

$action = '';


if ($_POST["login"]) {
  $name = $_POST["name"];
	$email = $_POST["email"];
	$password = $_POST["password"];
	$repassword = $_POST['repassword'];


	$name = stripslashes($name);
	$email = stripcslashes($email);
	$password = stripcslashes($password);
	$repassword = stripcslashes($repassword);

if(!ctype_alnum($name)) {
 
 $fehler['name'] = "<font color=#cc3333>Please enter your <strong>name</strong>.<br /></font>";
 
}

if (!preg_match("/^[0-9a-zA-ZÄÜÖ_.-]+@[0-9a-z.-]+\.[a-z]{2,6}$/", $email)) {
   $fehler['email'] = "<font color=#cc3333>Please enter a <strong>e-mail-address</strong>.\n<br /></font>";
}


if(!$password){
	$fehler['password'] = "<font color=#cc3333>Please enter your Password.\n<br /></font>";	
}

if(strlen($password)<8){
  $fehler['password'] = "<font color=#cc3333>Password too short.\n<br /></font>";  
}

if($repassword != $password){
	$fehler['repassword'] = "<font color=#cc3333>The pasword is not matching.\n<br /></font>";	
}



$database = new medoo([
  'database_type' => 'mysql',
  'database_name' => $dbname,
  'server' => $servername,
  'username' => $username,
  'password' => $dbpassword
  ]);



//$datas = $database->select("tmpapplicant", "*");
$countuser = $database->count("applicant", ["username" => $name ]);

if($countuser>0){
  $fehler['name'] = "<font color=#cc3333><strong>User Name</strong> already exists..<br /></font>";
}

$countemail = $database->count("tmpapplicant",["email" => $email]);

if($countemail > 0){
 $fehler['email'] = "<font color=#cc3333><strong>e-mail-address</strong> already exists.\n<br /></font>";  
}


if(!isset($fehler)){
  //echo "done";
  
  //include("validatelogin.php");
  $action = "upload.php";
  $passwordhash = md5($password);

  
}


}




?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de-DE" lang="de-DE">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="language"       content="us"/>
<meta name="description"      content="begc.co.in"/>
<meta name="robots"           content="INDEX,FOLLOW"/>
<meta http-equiv="Content-Style-Type" content="text/css" />   
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<title>Begc Step 1: Registration </title>
<link href="style-contact-form.css" rel="stylesheet" type="text/css" />

<script type='text/javascript'>
  
  var action = '<?php echo $action; ?>';
  function submittonext() {
      if(action == '') {
        return;
      }
      document.getElementById("myForm").submit();
    
    }


</script>

<body id="Kontaktformularseite" onload="submittonext()">
	<div class="kontaktformular" >
<center><img style="float: auto; text-align: center" src="font/logo.png" alt="Bio-Chemic Education Grant Commission, Govt. of India"/></center>
<center>Bakshi Lane, Bowbazar, P.O: Krishnanagar, Dist.: Nadia, W.B -741101</center>

<form action="<?php echo $action; ?>" method="post" id ='myForm' name="myForm" >

	<fieldset class="kontaktdaten">
<legend>Step 1: User Registration</legend>
 <table>
    <tr>
		<td class="label"><label>Username: <span class="pflichtfeld">*</span></label></td>
      	<td class="field"> <?php if ($fehler["name"] != "") { echo $fehler["name"]; } ?><input type="text" name="name" maxlength="25" id="textfield" value="<?php echo $_POST[name]; ?>" size="20" <?php if ($fehler["name"] != "") { echo 'class="errordesignfields"'; } ?>/></td>
      </tr>

      <tr>
      	<td class="label"><label>E-mail ID: <span class="pflichtfeld">*</span></label></td>
      	<td class="field"><?php if ($fehler["email"] != "") { echo $fehler["email"]; } ?><input type="text" name="email" maxlength="200" value="<?php echo $_POST[email]; ?>" size="20" <?php if ($fehler["email"] != "") { echo 'class="errordesignfields"'; } ?>/></td>
       </tr>

        <tr>
        <td class="label"><label>Password: <span class="pflichtfeld">*</span></label></td>
      	<td class="field"><?php if ($fehler["password"] != "") { echo $fehler["password"]; } ?><input type="password" name="password" maxlength="200" value="" size="20" <?php if ($fehler["password"] != "") { echo 'class="errordesignfields"'; } ?>/></td>
       </tr>

       <tr>
       <td class="label"><label>Re-enter Password: <span class="pflichtfeld">*</span></label></td>
      	<td class="field"><?php if ($fehler["repassword"] != "") { echo $fehler["repassword"]; } ?><input type="password" name="repassword" maxlength="200" value="" size="20" <?php if ($fehler["repassword"] != "") { echo 'class="errordesignfields"'; } ?>/></td>
       </tr>

       

   </table>
</fieldset>

<fieldset class="buttons">
   <legend>your action</legend>
   Remember the password, this will allow you to login to the system later.</br>

   <input type="hidden" name="passwordhash" value="<?php echo $passwordhash;?>" />
   <input type="submit" name="login" value="Submit" />
   <input type="submit" name="delete" value="Delete" />
   </fieldset>

  </form>


<div style="font-size:11px; text-align:center"><!-- It´s not allowed to remove the copyright notice! --><strong>Copyright</strong>: &copy; 2015 - 2020 <a href="http://www.begc.co.in/" title="Contact Form"> Bio-chemic Education Grant Commission, Govt of India. </a> </div>
</div>

</body>

</html>
