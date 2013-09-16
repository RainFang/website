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

	if (isset($_SESSION['username']))
	{
		echo "<table border='1'>";  
		echo "<tr>";
		echo "<td>".$_SESSION['username']."</td>";
		echo "<td><a href=http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/profile.php?Name=".$_SESSION['username'].">Profile Link</a></td>";
		echo "<td><a href=http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/editProfile.php>Edit Profile Link</a></td>";
		echo "<td><a href=http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/search.php>Search Link</a></td>";
		echo "<td><a href=http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/logout.php>Logout Link</a></td>";
		echo "</tr>";
		echo "</table>";
		echo "<br /><br /><br />";
		
		$edit_pw = False;
		
		//Validate input.
		$error_message_password = "";
		$error_message_passwordValid = "";
		$error_message_passwordMatch = "";
		
		if ( $_POST )
		{
			if ($_POST['pw'] == "" ) 
			{ 
				//They forgot to input their password
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
					else
					{
						$name = $_SESSION['username'];
						$query = "update userinfo set pw='".$_POST['pw']."', tv='".$_POST['tv']."', rel='".$_POST['rel']."', job='".$_POST['job']."' where username='".$name."';";
						$stmt = $db_server->prepare($query);
						$stmt->execute();
						$db_server->commit();
						$stmt->close();
						$destination_url = "profile.php?Name=$name";
						header("Location:$destination_url");
						exit();
					}
				}
			}
		}
		else
		{
			$query = "select * from userinfo where username='".$_SESSION['username']."'";
			$stmt = $db_server->query($query);
			$row = $stmt->fetch_row();

						
			$_POST['Name'] = $row[0]; 
			$_POST['pw'] = $row[1]; 
			$_POST['tv']= $row[2]; 
			$_POST['rel'] = $row[3];
			$_POST['job'] = $row[4];
		}
	}
	else
	{
		echo "<font color='red'>You must be logged in to edit a profile</font><br />";
		echo "<td><a href=http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/login.php>Login Link</a></td>";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html> 
  <head> 
    <title>Machine Registration Page</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head> 
  <body>
	<?php
    //Any error messages to display?
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
	<form method="post" action="editProfile.php">
	<fieldset>
	<legend> Account Registration </legend>
	Machine, your designation is:
	
	<?php
		print $_SESSION['username'];
	?> <br /> <br />

	Please input an alphabetic password (letters only, case sensitive):
	<input type="password" name="pw" 
	<?php
        if ( $_POST['pw'] ) 
		{
			print ' value="' . $_POST['pw'] . '"';
		}
    ?>> <br />  <br />
		
	Please verify the password:
	<input type="password" name="Verify" 
	<?php
        if ( $_POST['Verify'] ) 
		{
			print ' value="' . $_POST['Verify'] . '"';
		}
    ?>
	> </fieldset> <br />  <br />

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
			<input type="submit" name="submitted" value="Save Changes" />
      </form>
  </body>
</html>
