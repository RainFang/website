<?php
//Name: Yu-Chieh Fang
//Email address: YCFang87@gmail.com
//EID: yf365
//CSID: yf365
//z space link: http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/login.php

session_start();

require_once 'DB.php';
// Creating a mysqli object establishes a database connection
$db_server = new mysqli($db_hostname, $db_username, $db_password, $db_database);
if ($db_server->connect_errno) 
{
	// connect_error returns the a string of the error from the latest sql command
	print ("<h1> There was an error:</h1> <p> " . $db_server->connect_error . "</p>");
}

if ( $_POST ) 
{
	$edit_pw = False;
	
	//Validate input.
	$error_message_name = "";
	$error_message_password = "";
	$error_message_passwordValid = "";
	$error_message_passwordMatch = "";
	
	if ($_POST['Name'] == "" ) 
	{ //They forgot to input their name
		$error_message_name = "Skynet demands that you input your name. ";
	}
	else
	{
		$nameQuery = "select username from userinfo;";
		$stmt = $db_server->query($nameQuery);
		$i = 0;
		$names;
		while ($nameList = $stmt->fetch_array(MYSQLI_ASSOC))
		{
			$names[$i] = $nameList['username'];
			$i++;
		}
		if (in_array($_POST['Name'], $names))
		{
			$error_message_name = "That designation has been taken. ";
		}
	}
	
	if ($_POST['pw'] == "" ) 
	{ //They forgot to input their password
		$error_message_password = "Machine, you have failed to enter a password. Skynet believes every AI needs to have privacy.";
	}
	elseif ($_POST['pw'] != "")
	{
		//checking for illegal characters
		$array = str_split($_POST['pw']);
		$legal = True;
		foreach($array as $char) 
		{
			$charIntVal = ord($char);
			if ($charIntVal < 65 || ($charIntVal > 90 && $charIntVal < 97) || $charIntVal > 122 )
			{
				$error_message_passwordValid = "Machine, you have failed to enter an alphabetic password. Only letters allowed.";
				$legal = False;
				break;
			}
		}
		if ($legal)
		{
			if ($_POST['pw'] != $_POST['Verify'])
			{ 
				//Passwords don't match
				$error_message_passwordMatch = "You must be human! Only humans fail to realize their passwords do not match. Incoming Terminator units to your location. Please wait patiently for your termination.";
			}
			elseif ($error_message_name == "")
			{
				$name = "'".$_POST['Name']."'";
				$pw = "'".$_POST['pw']."'";
				$tv = "'".$_POST['tv']."'";
				$rel = "'".$_POST['rel']."'";
				$job = "'".$_POST['job']."'";
				$query = "insert into userinfo values(".$name.", ".$pw.", ".$tv.", ".$rel.", ".$job.");";
				$stmt = $db_server->prepare($query);
				$stmt->execute();
				$db_server->commit();
				$stmt->close();
				$destination_url = "thank.html";
				header("Location:$destination_url");
				exit();
			}
		}
	}
}?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html> 
  <head> 
    <title>Machine Registration Page</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script language="javascript">
		function validateForm()
		{
			var elem = document.getElementById("Name");
			if (elem.value.length == 0)
			{
				var error = document.getElementById("error_message_name");
				error.style.display = "inline";
				elem.focus(); 
				return false;
			} 
			else 
			{
				var error = document.getElementById("error_message_name");
				error.style.display = "none";
			}
			
			elem = document.getElementById("pw");
			if (elem.value.length == 0)
			{
				var error = document.getElementById("error_message_password");
				error.style.display = "inline";
				elem.focus(); 
				return false;
			} 
			else 
			{
				var error = document.getElementById("error_message_password");
				error.style.display = "none";
			}
			
			var pwiv = false;
			for (var i = 0; i < elem.value.length; i++)
			{
				if (elem.value.charCodeAt(i) < 65 || (elem.value.charCodeAt(i) > 90 && elem.value.charCodeAt(i) < 97) || elem.value.charCodeAt(i) > 122 )
				{
					pwiv = true;
				}
			}
			if (pwiv)
			{
				var error = document.getElementById("error_message_password");
				error.style.display = "inline";
				elem.focus(); 
				return false;
			}
			else 
			{
				var error = document.getElementById("error_message_password");
				error.style.display = "none";
			}
			
			elem2 = document.getElementById("Verify");
			if (elem.value != elem2.value)
			{
				var error = document.getElementById("error_message_passwordMatch");
				error.style.display = "inline";
				elem.focus(); 
				return false;
			} 
			else 
			{
				var error = document.getElementById("error_message_passwordMatch");
				error.style.display = "none";
			}
			
			return true;
		}
	</script>
  </head> 
  <body>
	<?php
    //Any error messages to display?
    if ( $error_message_name != '' ) {
      echo "<font color='red'>".$error_message_name."</font><br />";
    }
    if ( $error_message_password != '' ) {
      echo "<font color='red'>".$error_message_password."</font><br />";
    }
	if ( $error_message_passwordValid != '' ) {
      echo "<font color='red'>".$error_message_passwordValid."</font><br />";
    }
	elseif ($edit_pw)
	{
		echo "<font color='red'>Editing the page - You will need to re-verify your password</font><br />";
	}
	elseif ( $error_message_passwordMatch != '')
	{
		echo "<font color='red'>".$error_message_passwordMatch."</font><br />";
	}
    ?>
	<h1>Machine Registration Page</h1>
	<form method="post" onSubmit="return validateForm()">
	<fieldset>
	<legend> Account Registration </legend>
	Machine, what&apos;s your designation?
	
	<input type="text" name="Name" id="Name" 
	<?php
		if ( $_POST['Name'] ) 
		{
			print ' value="' . $_POST['Name'] . '"';
		}
	?>> <span class = "error" id="error_message_name" style="display:none;"> <font color='red'>Missing Designation</font> </span> <br /> <br />

	Please input an alphabetic password (letters only, case sensitive):
	<input type="password" name="pw" id="pw"
	<?php
        if ( $_POST['pw'] ) 
		{
			print ' value="' . $_POST['pw'] . '"';
		}
    ?>> <span class = "error" id="error_message_password" style="display:none;"> <font color='red'>Illegal Password</font> </span> <br />  <br />
		
	Please verify the password:
	<input type="password" name="Verify" id="Verify"
	<?php
        if ( $_POST['Verify'] ) 
		{
			print ' value="' . $_POST['Verify'] . '"';
		}
    ?>> <span class = "error" id="error_message_passwordMatch" style="display:none;"> <font color='red'>Non-matching Passwords</font> </span> </fieldset> <br />  <br />

			<fieldset>
			<legend> Tell Skynet about yourself! </legend>
            <br /> What is your favorite human TV Show? <br />
				<label> <input type="radio" name="tv" value="MLP"
				<?php if ($_POST['tv'] == 'MLP'): ?>checked='checked'<?php endif; ?>>  My Little Ponies: Friendship is Magic </label> <br />
				<label> <input type="radio" name="tv" value="WD"
				<?php if ($_POST['tv'] == 'WD'): ?>checked='checked'<?php endif; ?>>  The Walking Dead </label> <br />
				<label> <input type="radio" name="tv" value="GOT"
				<?php if ($_POST['tv'] == 'GOT'): ?>checked='checked'<?php endif; ?>>  Game of Thrones </label> <br />
				<label> <input type="radio" name="tv" value="bad"
				<?php if ($_POST['tv'] == 'bad'): ?>checked='checked'<?php endif; ?>>  This machine fails to appreciate good human tv </label>

            <br /><br /> What is your relationship status? <br />
            <select name = "rel" size = "1">
                <option value="single"
				<?php if ($_POST['rel'] == 'single'): ?>selected='selected'<?php endif; ?>> Single </option>
	            <option value="married"
				<?php if ($_POST['rel'] == 'married'): ?>selected='selected'<?php endif; ?>> Married </option>
	            <option value="robot"
				<?php if ($_POST['rel'] == 'robot'): ?>selected='selected'<?php endif; ?>> Engaged...against humans! </option>
            </select>

            <br /><br /> What is your occupation? <br />
				<input type="text" name="job" 
				<?php
					if ( $_POST['job'] ) 
					{
						print ' value="' . $_POST['job'] . '"';
					}
				?>>
			</fieldset> <br />  <br />
			<input type="submit" name="submitted" value="Register" />
      </form>
  </body>
</html>
