<?php // <--- do NOT put anything before this PHP tag
	include('functions.php');
	$cookieMessage = getCookieMessage();
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8" /> 
	<title>Sign Up Page</title>
	<link rel="stylesheet" type="text/css" href="shopstyle.css" />
</head>
	<?php
		include('NavBar.php');
	?>	
<body>
	<h1>Sign Up!</h1>
	<?php
		// display any error messages. TODO style this message so that it is noticeable.
		echo $cookieMessage;
	?>
	
	<form action = "AddNewCustomer.php" method = "POST">
		<!-- 
			TODO make a sign up <form>, don't forget to use <label> tags, <fieldset> tags and placeholder text. 
			all inputs are required.
			
			Make sure you <input> tag names match the names in AddNewCustomer.php
			
			your form tag should use the POST method. don't forget to specify the action attribute.
		-->
		<!--Done-->
		<fieldset>
			<legend>User Details</legend>
			<label for="username">User Name: </label>
			<input type="text" name="UserName" id="username" placeholder="User Name" required></input><br/>
			<br/>
			<label for="firstname">First Name: </label>
			<input type="text" name="FirstName" id="firstname" placeholder="First Name" required></input><br/>
			<br/>
			<label for="lastname">Last Name: </label>
			<input type="text" name="LastName" id="lastname" placeholder="Last Name" required></input><br/>
			<br/>
			<label for="address">Address: </label>
			<input type="text" name="Address" id="address" placeholder="Address" required></input><br/>
			<br/>
			<label for="city">City: </label>
			<input type="text" name="City" id="city" placeholder="City" required></input><br/>
			<br/>
			<input type="Submit" />
		</fieldset>
	</form>
	
</body>
</html>