<!DOCTYPE html>
<html>
<head>
	<title>Dragon's Den Game</title>
</head>
<body>
<?php
$q=$_GET["q"];

$con = mysqli_connect('localhost','root','','my_db');
if (!$con)
  {
  die('Could not connect: ' . mysqli_error($con));
  }

mysqli_select_db($con,"my_db");
$sql="SELECT * FROM Player WHERE Name = '".$q."'";

$result = mysqli_query($con,$sql);
if(!$result)
{
	print "Could not retrive player data: " . mysqli_error($con);
}

// Retrieve wins and level from here
$row = mysql_fetch_array($result);

// Retrieve list of monsters to battle 
$sqlM="SELECT * FROM Monster WHERE Level BETWEEN 0 and $row['Level']";
$resultM= mysqli_query($con,$sqlM);
// Count the results
$count=mysqli_num_rows($resultM);
// To grab the monster rows
$rowM = 0;
$mName = '';
$counter = 0;
// If the results are greater than one run a random operator if a win update the character
if($count>=1)
{
	// Generate a random number between 0 and the number of monsters to randomly choose the monster to battle
	$rand = rand(0,$count);
	while($rowM = mysql_fetch_array($resultM))
	{
		if($rand==$counter)
		{
			$mName = $rowM['Name'];
			break;
		}
		$counter++;
	}

}
else
{
	$mName = $rowM['Name'];
}

// Have a random battle to see if the player defeats the monster or not
if(rand(0,1))
{
	// Update the win count
	$wins = $row['Wins'] + 1;
	
	// Call the sql update
	$sql = "UPDATE Persons SET Wins=$wins WHERE Name='".$q."'";
	if(!mysqli_query($con,$sql))
	{
		print "Could not update player's win count: " . mysqli_error($con);
	}
	
	print "You defeated a $mName!!<br>";
}
else
{
	print "You lost to a $mName!!";
}
// If the character wins the battle and has a modulus of 0 from 10 level up the character and update
if($row['Wins'] % 10 == 0)
{
	// Update the player's level
	$level = $row['Level'] + 1;
	
	// Call the sql update
	$sql = "UPDATE Persons SET Level=$level WHERE Name='".$q."'";
	if(!mysqli_query($con,$sql))
	{
		print "Could not update player's level: " . mysqli_error($con);
	}
}

$sql="SELECT * FROM Player WHERE Name = '".$q."'";

$result = mysqli_query($con,$sql);

// Print the results
print "<table border='1'>
<tr>
<th>Name</th>
<th>Level</th>
<th>Wins</th>
</tr>";

while($row = mysql_fetch_array($result))
  {
  print "<tr>";
  print "<td>" . $row['Name'] . "</td>";
  print "<td>" . $row['Level'] . "</td>";
  print "<td>" . $row['Wins'] . "</td>";
  print "</tr>";
  }
print "</table>";

mysqli_close($con);
?> 
</body>
</html>