<?php
//Name: Yu-Chieh Fang
//Email address: YCFang87@gmail.com
//EID: yf365
//CSID: yf365
//z space link: http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/login.php

	require_once 'DB.php';
	// Creating a mysqli object establishes a database connection
    $db_server = new mysqli($db_hostname, $db_username, $db_password, $db_database);
    if ($db_server->connect_errno) {
       // connect_error returns the a string of the error from the latest sql command
       print ("<h1> There was an error:</h1> <p> " . $db_server->connect_error . "</p>");
     }
	
	$userNameValid = $passwordValid = False;
	$userNameErr = $passwordErr = "";
	$userNameVal = $passwordVal = "";
	
	//validating login

	if ($_SERVER["REQUEST_METHOD"] == "POST") 
	{
		if ((empty($_POST["username"]))) 
		{
					echo "<font color='red'>Missing username</font><br />";
		}
		else if (isset($_POST['username']) && strlen($_POST['username']) > 0){
					$queryVal = "'".$_POST['username']."'";
					$query = "select username from userinfo where username=".$queryVal;
					$stmt = $db_server->prepare($query);
   					$stmt->execute();
    				$stmt->store_result();
    				if ($stmt->num_rows == 1)
					{ 
						$userNameVal = $_POST['username'];
						$userNameValid = True;
					} 
					else 
					{
						echo "<font color='red'>Invalid username</font><br />";
					}
		}
		
		if ((empty($_POST["password"]))) 
		{
					echo "<font color='red'>Missing password</font><br />";
		}
		else if (isset($_POST['password']) && strlen($_POST['password']) > 0)
		{
					$userQueryVal = "'".$_POST['username']."'";
					$queryVal = "'".$_POST['password']."'";
					$query = "select username, pw from userinfo where username=".$userQueryVal." and pw=".$queryVal;
					$stmt = $db_server->prepare($query);
   					$stmt->execute();
    				$stmt->store_result();
    				if ($stmt->num_rows == 1)
					{ 
						$passwordValid = True;	
					} 
					else 
					{
						echo "<font color='red'>Invalid password</font><br />";
					}
		} 
			
	}
	
	if ($userNameValid && $passwordValid) 
	{
				session_start();
				$_SESSION['username'] = $userNameVal;
				header("Location: profile.php?Name=".$userNameVal);
	} 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html> 
    <head>
        <title>Skynet</title>
    </head>
    <body>
		<form action='login.php' method='post'>
		  <label for='username'> Username </label>
		  <input type='text' name='username' 
			<?php
				if ( $_POST['username'] ) 
				{
					print ' value="' . $_POST['username'] . '"';
				}
			?>>
		  <br />
		  <label for='password'> Password </label>
		  <input type='password' name='password' 
		  <?php
				if ( $_POST['password'] ) 
				{
					print ' value="' . $_POST['password'] . '"';
				}
			?>>
		  <br />
		  <input type='submit' name='login' value='Login'/> <br />
		</form>		
		<img border="0" src="http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/images/Terminator.jpg" alt="Skynet" width="300" height="200">
        <h1>Skynet - The best way to connect to your AI brethren!</h1>
        <a href="http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/signup.php">Signup Page Link!</a> 
    </body>
</html>
