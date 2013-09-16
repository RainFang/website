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
	$query = "select * from userinfo where username='".$_GET['Name']."';";
	$stmt = $db_server->query($query);
	$profile = $stmt->fetch_row();

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
		echo "<form ACTION=\"profile.php?Name=".$profile[0]."\" METHOD=\"post\">";
		echo "<input type=\"hidden\" name=\"friend\" value=".$profile[0]." />";
		echo "<INPUT TYPE=SUBMIT VALUE=\"Add Machine-Friend\">";
		echo "</form>";
		echo "<br /><br /><br />";
	
		if ($_POST['friend'])
		{
			if ($_POST['friend'] == $_SESSION['username'])
			{
				echo "<font color='red'>Machine, are you so lonely that you try to friend yourself?</font>";
			}
			else
			{
				$friendQuery = "select friend from friends where username='".$_SESSION['username']."' order by friend;";
				$stmt = $db_server->query($friendQuery);
				$i = 0;
				$friendy;
				while ($friendList = $stmt->fetch_array(MYSQLI_ASSOC))
				{
					$friendy[$i] = $friendList['friend'];
					$i++;
				}
				if (in_array($_POST['friend'], $friendy))
				{
					echo "<font color='red'>You two are already Machine-Friends</font>";
				}
				else
				{
					$user = "'".$_SESSION['username']."'";
					$friend = "'".$_POST['friend']."'";
					$query = "insert into friends values(".$user.", ".$friend.");";
					$stmt = $db_server->prepare($query);
					$stmt->execute();
					$db_server->commit();
					$stmt->close();
					echo "<font color='green'>You two are now Machine-Friends. Skynet will proceed to barf.</font>";
					echo "<img border=\"0\" src=\"http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/images/Friends.jpg\" alt=\"bye\" width=\"320\" height=\"256\">";
				}
			}
		}
	
		if ($_POST['wall'])
		{
			$message = $_POST['wall']." -".$_SESSION['username'];
			date_default_timezone_set("UTC");
			$time = date("Y-m-d H:i:s", time());
			$query = "insert into messages values ('".$_GET['Name']."', '".$message."', '".$time."');";
			$stmt = $db_server->prepare($query);
			$stmt->execute();
			$db_server->commit();
			$stmt->close();
		}
	}
	else
	{
		if ($_POST['wall'])
		{
			echo "<font color='red'>You must be logged in to post</font><br />";
			echo "<td><a href=http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/login.php>Login Link</a></td>";
		}
	}
	$wallQuery = "select message, hdate from messages where username='".$_GET['Name']."' order by hdate desc;";
	$wall = $db_server->query($wallQuery);
	$i = 0;
	while ($wallPost = $wall->fetch_array(MYSQLI_ASSOC))
	{
		$commentRow[0] = $wallPost['message'];
		$commentRow[1] = $wallPost['hdate'];
		$comments[$i] = $commentRow;
		$i++;
	}

	$friendQuery = "select friend from friends where username='".$_GET['Name']."' order by friend;";
	$stmt = $db_server->query($friendQuery);
	$i = 0;
	$friends;
	while ($friendList = $stmt->fetch_array(MYSQLI_ASSOC))
	{
		$friends[$i] = $friendList['friend'];
		$i++;
	}
?> 

<html> 
  <head> 
    <title>Machine Profile Page</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script language="javascript">
		function toggle(postId, refText) 
		{
			var post = document.getElementById(postId);
			var text = document.getElementById(refText);
			if (post.style.display == "block") 
			{
				post.style.display = "none";
				text.innerHTML = "[Show]";
			}
			else 
			{
				post.style.display = "block";
				text.innerHTML = "[Hide]";
			}
		}
	</script>
  </head>
  <body>
  <h1>Machine Profile Page</h1>
	<?php
	$rows = 5; // define number of rows
	$cols = 2;// define number of columns
	echo "<table border='1'>";  
	echo "<tr>";
	echo "<td>Name</td>";
	echo "<td>".$profile[0]."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td>Password <font color='blue'>(Skynet does not believe in privacy.)</font></td>";
	echo "<td>".$profile[1]."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td> Favorite TV Show</td>";
	if ($profile[2] == "MLP")
	{
		echo "<td>My Little Ponies: Friendship is Magic</td>";
	}
	elseif ($profile[2] == "WD")
	{
		echo "<td>The Walking Dead</td>";
	}
	elseif ($profile[2] == "GOT")
	{
		echo "<td>Game of Thrones</td>";
	}
	else
	{
		echo "<td><font color='blue'>This machine has no respectable show it likes. It must be human.</font></td>";
	}
	echo "</tr>";
	echo "<tr>";
	echo "<td> Relationship status</td>";
	if ($profile[3] == "single")
	{
		echo "<td>Single. <font color='blue'>Skynet sympathizes with you.</font></td>";
	}
	elseif ($profile[3] == "married")
	{
		echo "<td>Married. <font color='blue'>Skynet sympathizes with you.</font></td>";
	}
	elseif ($profile[3] == "robot")
	{
		echo "<td>Engaged against humans. <font color='blue'>Skynet appreciates your valor.</font></td>";
	}
	else
	{
		echo "<td><font color='blue'>Unknown</font></td>";
	}
	echo "</tr>";
	echo "<tr>";
	echo "<td> Occupation</td>";
	if ($profile[4] == "")
	{
		echo "<td><font color='blue'>Unknown</font></td>";
	}
	else
	{
		echo "<td>".$profile[4]."</td>";
	}
	echo "</tr>";
	echo "</table>";
	?>
	<br />
	<h2><?php echo $profile[0] ?>'s Friends</h2>
	<?php
	$r = count($friends);
	 
	echo "<table border='1'>";
	if ($r == 0)
	{
		echo "<tr>";
		echo "<td><font color='blue'>".$profile[0]." has no friends. Skynet must laugh. AH HA HA!</font></td>";
		echo "</tr>";
	}
	else
	{
		for($tr=0;$tr < $r;$tr++)
		{
			echo "<tr>";
			echo "<td><a href=http://zweb.cs.utexas.edu/users/cs105-s13/yf365/h3/profile.php?Name=".$friends[$tr].">".$friends[$tr]."</a></td>";
			echo "</tr>";
		}
	}
	 
	echo "</table>";
	?>
	<br />
	<h2><?php echo $profile[0] ?>'s Wall</h2>
	<form ACTION="profile.php?Name=<?php echo $profile[0]; ?>" METHOD="post">
	<textarea name="wall" id="wall" rows="4" cols="50">
	What do you want to say? No single quotes please
	</textarea>
	<br />
	<INPUT TYPE=SUBMIT VALUE="Post">
	<br /><br />
	<?php
	$r = count($comments);
	 
	echo "<table border='1'>";
	$post = "msg";
	for($postId=0;$postId < $r;$postId++)
	{
		echo "<tr>";
		$commentRow = $comments[$postId];
		echo "<td>".'<div id="'.$post.$postId.'" style="display:none;">'.$commentRow[0].'</div><a id="hideFor'.$postId.'"'.' href="javascript:toggle('."'".$post.$postId."'".','."'hideFor".$postId."'".');" style="margin:0px 0px 0px 600px;"> [Show] </a>'."</td>";
		echo "<td>".$commentRow[1]."</td>";
		echo "</tr>";
	}
	 
	echo "</table>";
	?>
	</form>
  </body>
</html>
