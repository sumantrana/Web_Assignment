<!DOCTYPE HTML>
<html>
<head>
	<title>Order Details</title>
	<link rel="stylesheet" type="text/css" href="shopstyle.css" />
	<meta charset="UTF-8" /> 
</head>
<body>
	<?php
		include('NavBar.php');
	?>	

<?php

// did the user provided an OrderID via the URL?
if(isset($_GET['OrderID']))
{
	$UnsafeOrderID = $_GET['OrderID'];
	
	include('functions.php');
	$dbh = connectToDatabase();
	
	// select the order details and customer details. (you need to use an INNER JOIN)
	// but only show the row WHERE the OrderID is equal to $UnsafeOrderID.
	$statement = $dbh->prepare('
		SELECT * 
		FROM Orders 
		INNER JOIN Customers ON Customers.CustomerID = Orders.CustomerID 
		WHERE OrderID = ? ; 
	');
	$statement->bindValue(1,$UnsafeOrderID);
	$statement->execute();
	
	// did we get any results?
	if($row1 = $statement->fetch(PDO::FETCH_ASSOC))
	{
		// Output the Order Details.
		$FirstName = makeOutputSafe($row1['FirstName']); 
		$LastName = makeOutputSafe($row1['LastName']); 
		$OrderID = makeOutputSafe($row1['OrderID']); 
		$UserName = makeOutputSafe($row1['UserName']); 
		$Address = makeOutputSafe($row1['Address']); 
		$City = makeOutputSafe($row1['City']); 
		$OrderTimeStamp = makeOutputSafe($row1['TimeStamp']); 
		
		// display the OrderID
		echo "<h2>OrderID: $OrderID</h2>";
		
		// its up to you how the data is displayed on the page. I have used a table as an example.
		// the first two are done for you.
		echo "<table>";
		echo "<tr><th>UserName:</th><td>$UserName</td></tr>";
		echo "<tr><th>Customer Name:</th><td>$FirstName $LastName </td></tr>";
		
		//TODO show the Customers Address and City.
		echo "<tr><th>Customer Address:</th><td>$Address </td></tr>";
		echo "<tr><th>Customer City:</th><td>$City </td></tr>";

		//TODO show the date and time of the order.
		echo "<tr><th>Order Date and Time:</th><td>$OrderTimeStamp </td></tr>";

		echo "</table>";
		
		// TODO: select all the products that are in this order (you need to use INNER JOIN)
		// this will involve three tables: OrderProducts, Products and Brands.
		$statement2 = $dbh->prepare('			
			SELECT * 
			FROM OrderProducts
			INNER JOIN Products on OrderProducts.ProductID = Products.ProductID
			INNER JOIN Brands on Products.BrandID = Brands.BrandID
			WHERE OrderID = ? ; 
		');
		$statement2->bindValue(1,$UnsafeOrderID);
		$statement2->execute();
		
		$totalPrice = 0;
		echo "<h2>Order Details:</h2>";
		
		// loop over the products in this order. 
		while($row2 = $statement2->fetch(PDO::FETCH_ASSOC))
		{
			//NOTE: pay close attention to the variable names.
			$ProductID = makeOutputSafe($row2['ProductID']); 
			$Description = makeOutputSafe($row2['Description']); 
			$Price = makeOutputSafe($row['Price']);
			$BrandName = makeOutputSafe($row['BrandName']);

			// TODO show the Products Description, Brand, Price, Picture of the Product and a picture of the Brand.
			// TODO The product Picture must also be a link to ViewProduct.php.

			
			// output the data in a div with a class of 'productBox' we can apply css to this class.
			echo "<div class = 'productBox'>";
			// [Put Task 5A here]  
			//echo "<img src = '../IFU_Assets/ProductPictures/$ProductID.jpg' alt ='' / >"; //Task 5A
			//Task 10A
			echo "<a href='./ViewProduct.php?ProductID=$ProductID'><img src='../IFU_Assets/ProductPictures/$ProductID.jpg' alt ='' /></a>  ";
			echo "$Description <br/>";
			echo "$Price <br/>";
			echo "$BrandName <br/>";
			echo "</div> \n";				
			
			// TODO add the price to the $totalPrice variable.
			$totalPrice += $Price;
		}		
		
		//TODO display the $totalPrice .
		echo "<div> \n";
		echo "Total Price: $totalPrice";
		echo "</div> \n";
		
	}
	else 
	{
		echo "System Error: OrderID not found";
	}
}
else
{
	echo "System Error: OrderID was not provided";
}
?>
</body>
</html>
