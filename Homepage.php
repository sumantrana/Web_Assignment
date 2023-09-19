<?php // <--- do NOT put anything before this PHP tag
	include('functions.php');
	$cookieMessage = getCookieMessage();
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8" /> 
	<title>SR Buy n Enjoy</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="shopstyle.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"></script>
	<script src="homepagecarousel.js"></script>
</head>
<body>
	<?php		
		include('TopDiv.php');
		include('NavBar.php');
	?>	
	<h1 class='ms-4'>Welcome</h1>
	<?php

		if ($cookieMessage){
			echo "<div class='alert alert-success' role='alert'>";
			echo "$cookieMessage";
			echo "</div>";
		}


		//Most popular items
		$dbh = connectToDatabase();

		$query = '
			SELECT OrderProducts.ProductID as ProductID, Description, count(*)
			FROM OrderProducts
			INNER JOIN Products on OrderProducts.ProductID = Products.ProductID
			Group BY OrderProducts.ProductID
			Order by count(*) DESC
			Limit 20
		';

		// echo "<div style='height: 100px;'>";
		echo "<div>";
		echo "<h3 class='ms-4 pt-4'>Most Popular Items</h3>";
		createCarousel($dbh,$query,'mostPopularProductsCarousel');


		//Recently bought items
		$query = '
			SELECT OrderProducts.ProductID as ProductID, Description
			FROM OrderProducts
			INNER JOIN Products on OrderProducts.ProductID = Products.ProductID
			INNER JOIN Orders on Orders.OrderID = OrderProducts.OrderID
			Order by Timestamp DESC
			Limit 20
		';

		echo "<h3 class='ms-4 pt-4'>Recently Bought By Others</h3>";
		createCarousel($dbh,$query,'recentlyBoughtProductsCarousel');

		echo "</div>";


		function createCarousel($dbh, $query, $name){

			$statement = $dbh->prepare($query);

			$statement->execute();
			
			echo "<div id='$name' class='carousel h-25'>";
			echo "<div class='carousel-inner'>";
					
			$counter = 0;
			while($row = $statement->fetch(PDO::FETCH_ASSOC)){

				$productID = makeOutputSafe($row['ProductID']);
				$productDescription = makeOutputSafe($row['Description']);

				if ( $counter != 0 ){
					echo "<div class='carousel-item'>";			
				} else {
					echo "<div class='carousel-item active'>";
				}			

				echo "<div class='card h-100 border'>";
				echo "<div class='img-wrapper'><img src='../IFU_Assets/ProductPictures/$productID.jpg' class='d-block w-50' alt=''></div>";
				echo "<div class='card-body'>";
				echo "<p class='card-text text-center'><a href='./ViewProduct.php?ProductID=$productID'>$productDescription</a></p>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
				
				$counter += 1;

			}

			echo "</div>";

			echo "<button class='carousel-control-prev' type='button' data-bs-target='#$name' data-bs-slide='prev'>";
			echo "<span class='carousel-control-prev-icon' aria-hidden='true'></span>";
			echo "<span class='visually-hidden'>Previous</span>";
			echo "</button>";
			
			echo "<button class='carousel-control-next' type='button' data-bs-target='#$name' data-bs-slide='next'>";
			echo "<span class='carousel-control-next-icon' aria-hidden='true'></span>";
			echo "<span class='visually-hidden'>Next</span>";
			echo "</button>";		

			echo "</div>";			


		}

	?>

</body>
</html>