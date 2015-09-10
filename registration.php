<?php
if($_SERVER['REQUEST_METHOD'] != 'POST'){
  echo "<h2>Not Found</h2>";
  echo "<hr><p>HTTP Error 404. The requested resource is not found.</p>";
exit;
}


session_start();
error_reporting(E_ERROR | E_PARSE);

//print_r($_POST); //debug only
header('Content-type: text/html; charset=utf-8');
  $script_root = substr(__FILE__, 0,
                        strrpos(__FILE__,
                                DIRECTORY_SEPARATOR)
                       ).DIRECTORY_SEPARATOR;



/*the configuration file set to define the 
mailserver, database and necessary content*/
@require('config.php');

/*This is required in order to clear the fiels
the post will have delete variable*/

if ($_POST['delete'])
{
unset($_POST);
}


$action='';

//break down post data
if ($_POST["mt-mk"]) {

// variables of the data fields
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
   $cast = $_POST["cast"];
   $nationality = $_POST["nationality"];
   $securitycode   = $_POST["securitycode"];
   $date = date("d.m.Y | H:i");
   $ip = $_SERVER['REMOTE_ADDR']; 
   $UserAgent = $_SERVER["HTTP_USER_AGENT"];
   $postapplying =$_POST["postapplying"];
   $amount = $_POST["amount"];


   

   //$host = getHostByAddr($remote);

   /*examination of the data fields
   for sql injections and hacking*/
$firstname = stripslashes($firstname);
$gname = stripslashes($gname);
$present_ad = stripslashes($present_ad);
$permanent_ad = stripslashes($permanent_ad);
$dob = stripcslashes($dob);
$phone = stripcslashes($phone);
$nationality = stripcslashes($nationality);


 



if(!$firstname) {
 
 $fehler["firstname"] = "<font color=#cc3333>Please enter your <strong>name</strong>.<br /></font>";
 
}

if(!$gname) {
 
 $fehler['gname'] = "<font color=#cc3333>Please enter your <strong>Guardian Name</strong>.<br /></font>";
 
}

if(!$present_ad) {
 
 $fehler['present_ad'] = "<font color=#cc3333>Please enter your <strong>Present Address</strong>.<br /></font>";
 
}

if(!$permanent_ad) {
 
 $fehler['permanent_ad'] = "<font color=#cc3333>Please enter your <strong>Permanent Address</strong>.<br /></font>";
 
}

if(!$dob){
  $fehler['dob'] = "<font color=#cc3333>Please enter your <strong>Date of Birth</strong>.<br /></font>";
}

if(!$nationality){
  $fehler['nationality'] = "<font color=#cc3333>Please enter your <strong>Nationality</strong>.<br /></font>";
}

/* Phone Number validation
$phone = '000-0000-0000';
validate phone number
regex for '000-0000-0000' "/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/", $phonenumber)) 
{*/
  if(!preg_match("/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i", $phone)) {
  $fehler['phone'] = "<font color=#cc3333>Please enter valid <strong>Phone Number</strong>.\n<br /></font>";
}


/*Validating e-mail-address*/
if (!preg_match("/^[0-9a-zA-ZÄÜÖ_.-]+@[0-9a-z.-]+\.[a-z]{2,6}$/", $email)) {
   $fehler['email'] = "<font color=#cc3333>Please enter a <strong>e-mail-address</strong></strong>.\n<br /></font>";
}


/***************************************************************************************************
*Captcha controll
*depend of form location
***************************************************************************************************/


if(empty($securitycode ) || strcasecmp($_SESSION['securitycode'], $securitycode) != 0){  
    unset($_SESSION['securitycode']);   
    $fehler['captcha'] = "<font color=#cc3333>You entered a wrong <strong>code</strong>.<br /></font>";

    }


 //print_r($fehler);

if (!isset($fehler)){
  //print_r($fehler);
$action = 'verify.php';
//do not include
}
//the breakup ends


//echo "<META HTTP-EQUIV=\"refresh\" content=\"0;URL=".$danke."\">";

//echo "<META HTTP-EQUIV=\"refresh\" content=\"0;URL=.thankyou.php";

//exit;
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


<title>Begc Registration Form fillup</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">

<link href="style-contact-form.css" rel="stylesheet" type="text/css" />

</head>
<script type='text/javascript'>
  
  var action = '<?php echo $action ?>';
    function submitPayuForm() {
      if(action == '') {
        return;
      }
      var payuForm = document.forms.payuForm;
      payuForm.submit();
    }


function refreshCaptcha(){
  var img = document.images['captchaimg'];
  img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}

function handleChange(cb) {
  if(cb.checked == true){
     document.getElementById("permanent_ad").value = document.getElementById("present_ad").value;
     document.getElementById("permanentslist").selectedIndex = document.getElementById("presentslist").selectedIndex;
  }else{
    document.getElementById("permanent_ad").value  = '';
  }
}

$(function() {
    $( "#datepicker" ).datepicker();
  });

var states = new Array("Andaman and Nicobar Islands", "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chandigarh", "Chhattisgarh", "Dadra and Nagar Haveli", "Daman and Diu", "Delhi", "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jammu and Kashmir", "Jharkhand", "Karnataka", "Kerala", "Lakshadweep", "Madhya Pradesh", "Maharashtra", "Manipur", "Meghalaya", "Mizoram", "Nagaland", "Orissa", "Pondicherry", "Punjab", "Rajasthan", "Sikkim", "Tamil Nadu", "Tripura", "Uttaranchal", "Uttar Pradesh", "West Bengal");


</script>

<body id="Kontaktformularseite" onload="submitPayuForm()">

<div class="kontaktformular">
<center><img style="float: auto; text-align: center" src="font/logo.png" alt="Bio-Chemic Education Grant Commission, Govt. of India"/></center>
<center>Bakshi Lane, Bowbazar, P.O: Krishnanagar, Dist.: Nadia, W.B -741101</center>


<form action="<?php echo $action; ?>" method="post" name="payuForm">


      <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
      <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
      <input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
      <input type="hidden" name="imagelocation" value="<?php echo $_POST[imagelocation]; ?>" />
      <input type="hidden" name="service_provider" value="payu_paisa" size="64" />
      <input type="hidden" name="productinfo" value="Registration form" size="64" />
      <input type="hidden" name="surl" value="<?php echo $surl; ?>"/>
      <input type="hidden" name="furl" value="<?php echo $furl; ?>"/>
      <input type="hidden" name="name" value="<?php echo $_POST[name];?>"/>
      <input type="hidden" name="passwordhash" value="<?php echo $_POST[passwordhash];?>" />


<!-- amount calculations  -->

      
      




<p><input style="width:0px; height:0px; visibility:hidden;" type="hidden" name="action" value="smail" /></p>
<p><input style="width:0px; height:0px; visibility:hidden;" type="hidden" name="content" value="formular"/></p>



<fieldset class="kontaktdaten">
    <legend>Applicant data</legend>
  <table>
    <tr>
      <td class="label"><label>Name: <span class="pflichtfeld">*</span></label></td>
      <td class="field"> <?php if ($fehler["firstname"] != "") { echo $fehler["firstname"]; } ?><input type="text" name="firstname" maxlength="25" id="textfield" value="<?php echo $_POST[firstname]; ?>" size="20" <?php if ($fehler["firstname"] != "") { echo 'class="errordesignfields"'; } ?>/></td>
    
      <td><div class='img'><img src="<?php echo $_POST[imagelocation]; ?>"></div>
        <div class="desc"><?php echo $_POST[name];?> </div>
      </td> 

    </tr>
    
    <tr>
      <td class="label"><label>Father`s /Husband /Guardian Name: <span class="pflichtfeld">*</span></label></td>
      <td class="field"> <?php if ($fehler["gname"] != "") { echo $fehler["gname"]; } ?><input type="text" name="gname" maxlength="25" id="textfield" value="<?php echo $_POST[gname]; ?>" size="20" <?php if ($fehler["gname"] != "") { echo 'class="errordesignfields"'; } ?>/></td>
    
    </tr>
    
    <tr>
      <td class="label"><label>Present Address: <span class="pflichtfeld">*</span></label></td>
        <td class="field"><?php if ($fehler["present_ad"] != "") { echo $fehler["present_ad"]; } ?><textarea name="present_ad" id="present_ad" cols="30" rows="8" <?php if ($fehler["present_ad"] != "") { echo 'class="errordesignfields"'; } ?>><?php echo $_POST[present_ad]; ?></textarea></td>
    </tr>

    <tr>
      <td class="label"><label>State <span class="pflichtfeld">*</span></label></td>
        <td><select id="presentslist" name="presentslist">
          <script type="text/javascript">
          for(var hi=0; hi<states.length; hi++)
          document.write("<option value=\""+states[hi]+"\">"+states[hi]+"</option>");
          </script>
          <?php
          if(isset($_POST["presentslist"])){
          $presentslist=$_POST["presentslist"];
            echo "<option value='" . $presentslist ."' selected>" . $presentslist ."</option>";
            
          }

          ?>
          </select></td>
    </tr>

    
    <tr>
      <td class="label"><label>Permanent address is same as present: <span class="pflichtfeld">*</span></label></td>
        <td><input type='checkbox' onchange='handleChange(this);'></td>
    </tr>

    <tr>
      <td class="label"><label>Permanent Address: <span class="pflichtfeld">*</span></label></td>
        <td class="field"><?php if ($fehler["permanent_ad"] != "") { echo $fehler["permanent_ad"]; } ?><textarea name="permanent_ad" id ="permanent_ad" cols="30" rows="8" <?php if ($fehler["permanent_ad"] != "") { echo 'class="errordesignfields"'; } ?>><?php echo $_POST[permanent_ad]; ?></textarea></td>
    </tr>

<tr>
      <td class="label"><label>State <span class="pflichtfeld">*</span></label></td>
        <td><select id="permanentslist" name="permanentslist">
          <script type="text/javascript">
          for(var hi=0; hi<states.length; hi++)
          document.write("<option value=\""+states[hi]+"\">"+states[hi]+"</option>");
          </script>
          <?php
          if(isset($_POST["permanentslist"])){
          $permanentslist=$_POST["permanentslist"];
            echo "<option value='" . $permanentslist ."' selected>" . $permanentslist ."</option>";
            
          }

          ?>          
          </select></td>
</tr>    

    <tr>
      <td class="label"><label>Birth date <span class="pflichtfeld">*</span></label></td>
      <td><?php if($fehler["dob"] !=""){ echo $fehler["dob"];} ?>
        <input type="text" id="datepicker" name="dob" <?php if($fehler["dob"] !=""){echo 'class="errordesignfields';} ?> value="<?php echo $_POST[dob]; ?>">
        
      </td>      
    </tr>
    
    <tr>
      <td class="label" ><label>Gender <span class="pflichtfeld">*</span></label></td>
       <?php
          $gender = $_POST['gender'];
      ?>
      <td> <select name = "gender">
          <option value="male" <?php if($gender == 'male'){echo("selected");}?>>Male</option>
          <option value="female" <?php if($gender == 'female'){echo("selected");}?>>Female</option>
          </select>
      </td>     
    </tr>
    
    <tr>
      <td class="label"><label>E-Mail: <span class="pflichtfeld">*</span></label></td>
      <td class="field"><?php if ($fehler["email"] != "") { echo $fehler["email"]; } ?><input type="text" name="email" maxlength="200" value="<?php echo $_POST[email]; ?>" size="20" <?php if ($fehler["email"] != "") { echo 'class="errordesignfields"'; } ?>/></td>
    </tr>
    <tr>
      <td class="label"><label>Phone Number: </label></td>
      <!-- <td class="field"><input type="text" name="phonenumber" maxlength="150" value="<?php echo $_POST[phonenumber]; ?>" size="20"/></td> -->
      <td class="field"><?php if ($fehler["phone"] != "") { echo $fehler["phone"]; } ?><input type="text" name="phone" maxlength="200" value="<?php echo $_POST[phone]; ?>" size="20" <?php if ($fehler["phone"] != "") { echo 'class="errordesignfields"'; } ?>/></td>
    </tr>

    <tr>
      <td class="label"><label>Nationality<span class="pflichtfeld">*</span></label></td>
      <td class="field"><?php if ($fehler["nationality"] != "") { echo $fehler["nationality"]; } ?><input type="text" name="nationality" maxlength="200" value="<?php echo $_POST[nationality]; ?>" size="20" <?php if ($fehler["nationality"] != "") { echo 'class="errordesignfields"'; } ?>/></td>
    </tr>

    <!-- Cast (SC/ST , GEN) -->
    <tr>
      <td class="label"><label>Cast<span class="pflichtfeld">*</span></label></td>
      <?php
          $usercast = $_POST['usercast'];
      ?>

      <td> <select autocomplete="off" name="usercast">
          <option value="gen" <?php if($usercast == 'gen'){echo("selected");}?> >Gen</option>
          <option value="sc" <?php if($usercast == 'sc'){echo("selected");}?>>SC</option>
          <option value="st" <?php if($usercast == 'st'){echo("selected");}?>>ST</option>
          <option value="obc" <?php if($usercast == 'obc'){echo("selected");}?>>OBC</option>
          </select>
      </td>     
    </tr>

    <tr>
      <td class="label"><label>Post Applying for<span class="pflichtfeld">*</span></label></td>
       <?php
          $postapplying = $_POST['postapplying'];
      ?>


      <td> <select name="postapplying">
          <option value="groupa" <?php if($postapplying == 'groupa'){echo("selected");}?> >Group A: Section Officer</option>
          <option value="groupc" <?php if($postapplying == 'groupc'){echo("selected");}?> >Groups C: Sevak / Sevika / Clerks</option>
          <option value="groupd" <?php if($postapplying == 'groupd'){echo("selected");}?>>Group D: Helper</option>
          </select>
      </td>     
    </tr>



    <tr>

      <td class="error"></td>
    </tr>
        
  </table>
 </fieldset>

 <fieldset class="anfrage">
    <legend>Qualification</legend>
  <table border="1">
    <tr>
      <td>Exam Pass*</td>
      <td>Year*</td>
      <td>Board/Council/University*</td>
      <td>Full Marks or Grade*</td>
      <td>Percentage*</td>
    </tr>
    <tr>
      <td class="field"><input type="text" name="exam1" maxlength="150" value="<?php echo $_POST[exam1]; ?>" /></td>
      <td class="field"><input type="text" name="year1" maxlength="10" value="<?php echo $_POST[year1]; ?>" /></td>
      <td class="field"><input type="text" name="board1" maxlength="150" value="<?php echo $_POST[board1]; ?>" /></td>
      <td class="field"><input type="text" name="marks1" maxlength="150" value="<?php echo $_POST[marks1]; ?>" /></td>
      <td class="field"><input type="text" name="percent1" maxlength="150" value="<?php echo $_POST[percent1]; ?>" /></td>
    </tr>
    
    <tr>
      <td class="field"><input type="text" name="exam2" maxlength="150" value="<?php echo $_POST[exam2]; ?>" /></td>
      <td class="field"><input type="text" name="year2" maxlength="10" value="<?php echo $_POST[year2]; ?>" /></td>
      <td class="field"><input type="text" name="board2" maxlength="150" value="<?php echo $_POST[board2]; ?>" /></td>
      <td class="field"><input type="text" name="marks2" maxlength="150" value="<?php echo $_POST[marks2]; ?>" /></td>
      <td class="field"><input type="text" name="percent2" maxlength="150" value="<?php echo $_POST[percent2]; ?>" /></td>
    </tr>
    
    <tr>
      <td class="field"><input type="text" name="exam3" maxlength="150" value="<?php echo $_POST[exam3]; ?>" /></td>
      <td class="field"><input type="text" name="year3" maxlength="10" value="<?php echo $_POST[year3]; ?>" /></td>
      <td class="field"><input type="text" name="board3" maxlength="150" value="<?php echo $_POST[board3]; ?>" /></td>
      <td class="field"><input type="text" name="marks3" maxlength="150" value="<?php echo $_POST[marks3]; ?>" /></td>
      <td class="field"><input type="text" name="percent3" maxlength="150" value="<?php echo $_POST[percent3]; ?>" /></td>
    </tr>
    
  </table>
 </fieldset>
 


 <fieldset class="captcha">
  <legend>spam-protection</legend>
  <table>
      
      <!-- The captcha is generated via captha.php !-->

    <tr>
      <td align="right" valign="top"> Validation code:</td>
        <td><img src="captcha.php?rand=<?php echo rand();?>" id='captchaimg'></td>
        <!--<input id="securitycode" name="securitycode" type="text"> !-->
        <br>
    </tr>
    
    <tr>
    <td class="label" ><label for='message'>Enter the code above here :</label></td>
    <td class="field" colspan="2"><?php if ($fehler["captcha"] != "") { echo $fehler["captcha"]; } ?><input type="text" name="securitycode" maxlength="150" value="" size="20" <?php if ($fehler["captcha"] != "") { echo 'class="errordesignfields"'; } ?>/>
    Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</td>
    </tr>

  
  </table>
 </fieldset>


 <fieldset class="buttons">
   <legend>your action</legend>
<br />
   <div style="text-align:center">Advice: Fields with <span class="pflichtfeld">*</span> have to be filled.<br /><br />

      <!-- <input type="submit" name="mt-mk" value="Send" onclick="tescht();"/> -->
          <?php if(!$hash) { 
            //print_r($fehler['amount']);

            ?>
            <td colspan="4"><input name="mt-mk" type="submit" value="Submit" /></td>
          <?php } ?>



   <input type="submit" name="delete" value="Delete" />
</div>

</fieldset>
</form>

 <div style="font-size:11px; text-align:center"><!-- It´s not allowed to remove the copyright notice! --><strong>Copyright</strong>: &copy; 2015 - 2020 <a href="http://www.begc.co.in/" title="Contact Form"> Bio-chemic Education Grant Commission, Govt of India. </a> </div></div>

</body>
</html>