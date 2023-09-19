<?php // <--- do NOT put anything before this PHP tag

include('functions.php');

// get the cookieMessage, this must be done before any HTML is sent to the browser.
$cookieMessage = getCookieMessage();

?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8" /> 
	<title>Cart</title>
	<link rel="stylesheet" type="text/css" href="shopstyle.css" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>
<body>
	<?php
		include('TopDiv.php');
		include('NavBar.php');
	?>		
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

	// does the user have items in the shopping cart?
	if(isset($_COOKIE['ShoppingCart']) && $_COOKIE['ShoppingCart'] != '')
	{
		// the shopping cart cookie contains a list of productIDs separated by commas
		// we need to split this string into an array by exploding it.
		$productID_list = explode(",", $_COOKIE['ShoppingCart']);
		
		// remove any duplicate items from the cart. although this should never happen we 
		// must make absolutely sure because if we don't we might get a primary key violation.
		$productID_list = array_unique($productID_list);
		
		$dbh = connectToDatabase();

		// create a SQL statement to select the product and brand info about a given ProductID
		// this SQL statement will be very similar to the one in ViewProduct.php
		$statement = $dbh->prepare('SELECT * FROM Products INNER JOIN Brands
		ON Brands.BrandID = Products.BrandID
		WHERE Products.ProductID = ? ');

		$totalPrice = 0;
		
		// loop over the productIDs that were in the shopping cart.
		foreach($productID_list as $productID)
		{
			// great thing about prepared statements is that we can use them multiple times.
			// bind the first question mark to the productID in the shopping cart.
			$statement->bindValue(1,$productID);
			$statement->execute();
			
			// did we find a match?
			if($row = $statement->fetch(PDO::FETCH_ASSOC))
			{				
				//TODO Output information about the product. including pictures, description, brand etc.				
				$Price = htmlspecialchars($row['Price'], ENT_QUOTES, 'UTF-8'); 
				$Description = htmlspecialchars($row['Description'], ENT_QUOTES, 'UTF-8'); 
				$BrandName = htmlspecialchars($row['BrandName'], ENT_QUOTES, 'UTF-8'); 
				$BrandID = htmlspecialchars($row['BrandID'], ENT_QUOTES, 'UTF-8'); 
				$BrandWebsite = htmlspecialchars($row['Website'], ENT_QUOTES, 'UTF-8');

				echo "<div class = 'productBox'>";	
				echo "<div class = 'row'>";
				echo "<div class = 'col col-1'>";	
				echo "<div class = 'row'>";
				echo "<img src = '../IFU_Assets/ProductPictures/$productID.jpg' alt= 'productID' /> <br/>";
				echo "</div>";
				echo "<div class = 'row'>";
				echo "<img class='thumbnail img-responsive' src = '../IFU_Assets/BrandPictures/$BrandID.jpg' alt='BrandID' /><br/>";
				echo "</div>";
				echo "</div>";
				echo "<div class = 'col-10'>";
				echo "$Description <br/>";
				echo "Price: $Price <br/>";
				echo "Brand: <a href='$BrandWebsite'>$BrandName</a> <br/>";
				echo "</div>";
				echo "</div>";
				
				//echo "<br>";
				
				echo "</div> \n";				

				//TODO add the price of this item to the $totalPrice
				
				$totalPrice += $Price;

			}
		}

		// TODO: output the $totalPrice.
		//Done
		echo "<div class = 'text-end fs-4 fw-bold pe-3'> \n";
		echo "Total Price: $totalPrice";
		echo "</div> \n";
		
		
		// you are allowed to stop and start the PHP tags so you don't need to use lots of echo statements.
		?>
				<div class = 'row'>
				<div class = 'col col-10'>	
				<form action = 'ProcessOrder.php' method = 'POST'>
				
					<!-- TODO put a text input here so the user can type in their UserName.
						this input tag MUST have its name attribute set to 'UserName' -->
					<!-- Done -->
					<!-- TODO put a submit button so the user can submit the form -->
					<!-- Done -->
					<fieldset>
					<div class = 'row border'>
						<legend>Confirm Order</legend>
						<div class = 'col col-1'>
							<label for="username">User Name: </label>
						</div>
						<div class = 'col col-2'>
							<input type="text" name="UserName" id="username" placeholder="User Name" required></input><br/>
						</div>
						<div class = 'col col-3'>
							<input type="Submit" value = 'Confirm Order'/>
						</div>
					</div>	
					</fieldset>

					
				</form>
				</div>
				<div class = 'col'>
				<form action = 'EmptyCart.php' method = 'POST'>
					<div class = 'col'>	
						<br/>
						<br/>
						<input type = 'submit' name = 'EmptyCart' value = 'Empty Shopping Cart' id = 'EmptyCart' />
					</div>
				</form>
				</div>
				</div>	
			</div>
		<?php 		
	}
	else
	{
		echo "<h2>You Have no items in your cart!</h2>";
	}
	?>
</body>
</html>
