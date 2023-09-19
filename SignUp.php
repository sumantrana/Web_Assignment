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
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>
	<?php
	    include('TopDiv.php');
		include('NavBar.php');
	?>	
<body>
	<h1>Sign Up!</h1>
	<?php
		if ($cookieMessage){
			// if we have any error messages echo them now. 
			//TODO style this message so that it is noticeable.
			if ( str_contains($cookieMessage, "Success") ){
				echo "<div class='alert alert-success' role='alert'>";
				echo "$cookieMessage";
				echo "</div>";
			} else {
				echo "<div class='alert alert-danger' role='alert'>";
				echo "$cookieMessage";
				echo "</div>";
			}
		}
	?>
	
	<div class="border border-3">
	<form action = "AddNewCustomer.php" method = "POST">
		<!-- 
			TODO make a sign up <form>, don't forget to use <label> tags, <fieldset> tags and placeholder text. 
			all inputs are required.
			
			Make sure you <input> tag names match the names in AddNewCustomer.php
			
			your form tag should use the POST method. don't forget to specify the action attribute.
		-->
		<!--Done-->
		<!-- <fieldset> -->
			<legend>User Details</legend>
			<div class="form-group w-50 ms-2">
				<label for="username">User Name: </label>
				<input type="text" class="form-control" name="UserName" id="username" placeholder="User Name" required></input><br/>
			</div>
			<div class="form-group w-50 ms-2">
			<label for="firstname">First Name: </label>
			<input type="text" class="form-control" name="FirstName" id="firstname" placeholder="First Name" required></input><br/>
			</div>
			<div class="form-group w-50 ms-2">
			<label for="lastname">Last Name: </label>
			<input type="text" class="form-control" name="LastName" id="lastname" placeholder="Last Name" required></input><br/>
			</div>
			<div class="form-group w-50 ms-2">
			<label for="address">Address: </label>
			<input type="text" class="form-control" name="Address" id="address" placeholder="Address" required></input><br/>
			</div>
			<div class="form-group w-50 ms-2">
			<label for="city">City: </label>
			<input type="text" class="form-control" name="City" id="city" placeholder="City" required></input><br/>
			</div>
			<input class="btn btn-primary ms-2" type="Submit" />
		<!-- </fieldset> -->
	</form>
	</div>
	
</body>
</html>