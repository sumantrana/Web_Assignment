<!doctype html>
<html>
<head>
	<meta charset="UTF-8" /> 
	<title>My First SQL Page</title>
	<link rel="stylesheet" type="text/css" href="shopstyle.css" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
	<?php
		include('TopDiv.php');
		include('NavBar.php');
		include('functions.php');
	?>	
	<h1>Product Details</h1>
	<?php 
		
		if(isset($_GET['ProductID']))
		{		
			$productIDURL = $_GET['ProductID'];	 // Task 10
			
			// connect to the database using our function (and enable errors, etc)
			$dbh = connectToDatabase(); 
			
			//  bind the value here
			$statement = $dbh->prepare('SELECT * FROM Products INNER JOIN Brands
			ON Brands.BrandID = Products.BrandID
			WHERE Products.ProductID = ? '); //Task 10  LIMIT 10 ; //OFFSET ? * 10 
			
			$statement->bindValue(1,$productIDURL);  // Task 10
			
			//execute the SQL.
			$statement->execute();

			// get the result, there will only ever be one product with a given ID (because products ids must be unique)
			// so we can just use an if() rather than a while()
			if($row = $statement->fetch(PDO::FETCH_ASSOC))
			{
				
				$ProductID = htmlspecialchars($row['ProductID'], ENT_QUOTES, 'UTF-8'); 
				$Price = htmlspecialchars($row['Price'], ENT_QUOTES, 'UTF-8'); 
				$Description = htmlspecialchars($row['Description'], ENT_QUOTES, 'UTF-8'); 
				$BrandName = htmlspecialchars($row['BrandName'], ENT_QUOTES, 'UTF-8'); 
				$BrandID = htmlspecialchars($row['BrandID'], ENT_QUOTES, 'UTF-8'); 
				$BrandWebsite = htmlspecialchars($row['Website'], ENT_QUOTES, 'UTF-8'); 


				// echo "<div class='card bg-light border-dark mb-3 ms-5 me-5'>";
				// echo "<div class='card-body'>";
				// echo "<img src = '../IFU_Assets/ProductPictures/$productIDURL.jpg' style='width: 18rem;' alt= 'productID' /> <br/>";
				// echo "<p class='card-text'>$Description</p>";
				// echo "<p class='card-text'>Price: $Price</p>";
				// echo "<p class='card-text'>Brand: $BrandName</p>";
				// echo "<a href='$BrandWebsite'>Brand Home: <img class='card-img-bottom' style='width: 5rem;' src='../IFU_Assets/BrandPictures/$BrandID.jpg' alt='BrandID'/> <br/>";

				echo "<div class = 'productBox'>";	
					echo "<div class = 'row'>";
						echo "<div class = 'col col-1'>";	
							echo "<div class = 'row'>";
								echo "<img src = '../IFU_Assets/ProductPictures/$productIDURL.jpg' alt= 'productID' /> <br/>";
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
					echo "<div class = 'row'>";	
						echo "<div class = 'col-4'>";		

						if(isset($_COOKIE['ShoppingCart']) && $_COOKIE['ShoppingCart'] != "")
						{
							// TODO: Get the list of items in the shopping cart.
							// and then check if product is already there.
							//Done
							$existingProducts = $_COOKIE['ShoppingCart'];

							if (str_contains($existingProducts, $ProductID) === false){
								echo "<form action='AddToCart.php?ProductID=$ProductID' method='post'>";
								echo "<button class='btn btn-primary p-2 m-2' type='submit' name='BuyButton'>Add to Cart</button>";
								echo "</form>";
							}
							

						} else {
							echo "<form action='AddToCart.php?ProductID=$ProductID' method='post'>";
							echo "<button class='btn btn-primary' type='submit' name='BuyButton'>Add to Cart</button>";
							echo "</form>";
						}	

				
						echo "</div>";
					echo "</div>";
				echo "</div>";					
				
			}
			else
			{
				echo "Unknown Product ID";
			}
		}
		else
		{
			echo "No ProductID provided!";
		}
	?>
</body>
</html>