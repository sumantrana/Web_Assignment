<!DOCTYPE HTML>
<html>
<head>
	<title>Order Details</title>
	<link rel="stylesheet" type="text/css" href="shopstyle.css" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<meta charset="UTF-8" /> 
</head>
<body>
<?php

include('TopDiv.php');
include('NavBar.php');
include('functions.php');

// did the user provided an OrderID via the URL?
if(isset($_GET['OrderID']))
{
	$UnsafeOrderID = $_GET['OrderID'];
	
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
		echo "<div class='border border-dark ms-1 me-1'>";
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
		$OrderTime = date('Y-m-d H:i:s', $OrderTimeStamp);
		echo "<tr><th>Order Date and Time:</th><td>$OrderTime</td></tr>";

		echo "</table>";
		echo "</div>";
		
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
		echo "<br />";
		echo "<br />";
		echo "<br />";
		echo "<h2>Order Details:</h2>";

		echo "<table class='table table-bordered'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th scope='col'>Product</th>";
        echo "<th scope='col'>Price</th>";
		echo "</tr>";
        echo "</thead>";
        echo "<tbody>"; 
		
		// loop over the products in this order. 
		while($row2 = $statement2->fetch(PDO::FETCH_ASSOC))
		{
			//NOTE: pay close attention to the variable names.
			$ProductID = makeOutputSafe($row2['ProductID']); 
			$Description = makeOutputSafe($row2['Description']); 
			$Price = makeOutputSafe($row2['Price']);
			$BrandName = makeOutputSafe($row2['BrandName']);
			$BrandID = makeOutputSafe($row2['BrandID']);

			// TODO show the Products Description, Brand, Price, Picture of the Product and a picture of the Brand.
			// TODO The product Picture must also be a link to ViewProduct.php.

			
			// output the data in a div with a class of 'productBox' we can apply css to this class.
			echo "<tr>";
			echo "<td>";
			echo "<div class = 'productBox'>";
			// [Put Task 5A here]  
			//echo "<img src = '../IFU_Assets/ProductPictures/$ProductID.jpg' alt ='' / >"; //Task 5A
			//Task 10A
			
			echo "<a href='./ViewProduct.php?ProductID=$ProductID'><img class='h-50' src='../IFU_Assets/ProductPictures/$ProductID.jpg' alt ='' /></a>  ";
			echo "<img src='../IFU_Assets/BrandPictures/$BrandID.jpg' alt='BrandID'/> <br/>";
			echo "$Description <br/>";
			//echo "$Price <br/>";
			echo "Brand: $BrandName <br/>";
			echo "</div> \n";	
			echo "</td>";
			echo "<td>$Price</td>";			
			echo "</tr>";			
			
			// TODO add the price to the $totalPrice variable.
			$totalPrice += $Price;
		}	
		

		
		//TODO display the $totalPrice .
		echo "<tr>";
		echo "<td class='text-end fs-4 fw-bold'>Total Price</td>";
		echo "<td class='fs-4 fw-bold'>$totalPrice</td>";
		echo "</tr>";

		echo "</tbody>"; 
        echo "</table>"; 
		
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
