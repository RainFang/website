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
}

if ( $_POST ) 
{
	$nameQuery = "select username from userinfo where username like '%".$_POST['Name']."%';";
	$stmt = $db_server->query($nameQuery);
	$i = 0;
	$names;
	while ($nameList = $stmt->fetch_array(MYSQLI_ASSOC))
	{
		$names[$i] = $nameList['username'];
		$i++;
	}
}
?>

<html> 
	<head> 
		<title>Photos Page</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
	<body>
		<h1>Machine Photo Page</h1>
		<br />
		<form method="post" action="search.php">
			<fieldset>
				<legend> Enter a machine to search </legend>
				<input type="text" name="Name" 
				<?php
					if ( $_POST['Name'] ) 
					{
						print ' value="' . $_POST['Name'] . '"';
					}
				?>>
			</fieldset>
			<input type="submit" name="submitted" value="Search" />
		</form>
		<?php
			echo "<h2>Machine Designation Search Results:</h2>";
			$r = count($names);
			 
			echo "<table border='1'>";
			 
			for($tr=0;$tr < $r;$tr++)
			{
				echo "<tr>";
				echo "<td><a href=http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/profile.php?Name=".$names[$tr].">".$names[$tr]."</a></td>";
				echo "</tr>";
			}
			 
			echo "</table>";
		?>
	</body>
</html>