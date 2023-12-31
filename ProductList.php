<!doctype html>
<html>
<head>
	<meta charset="UTF-8" /> 
	<title>My First SQL Page</title>
	<link rel="stylesheet" type="text/css" href="shopstyle.css" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
	<?php
		include('TopDiv.php');
		include('NavBar.php');
		include('functions.php');
	?>	
	<h1>Products List</h1>
	<?php 

		$popularity="Popularity";
		$az="Name: A to Z";
		$za="Name: Z to A";
		$priceup="Price: Low to High";
		$pricedown="Price: High to Low";	
		
		// if the user provided a search string.
		if(isset($_GET['search']))
		{
			$searchString = $_GET['search'];
		}
		// if the user did NOT provided a search string, assume an empty string
		else
		{
			$searchString = "";
		}
				
		$safeSearchString = htmlspecialchars($searchString, ENT_QUOTES,"UTF-8"); //Task 8B
		$SqlSearchString = "%$safeSearchString%"; //Task 8B

		$queryString = '
			SELECT Products.ProductID as ProductID, Description, Price, count(*)
			FROM Products
			INNER JOIN OrderProducts on OrderProducts.ProductID = Products.ProductID
			WHERE Products.Description LIKE ?
			Group BY OrderProducts.ProductID
			Order by count(*) DESC
			LIMIT 10 OFFSET ? * 10;		
		';

		if(isset($_GET['orderby']))
		{
			$orderByString = $_GET['orderby'];

			if ($orderByString == 'az'){
				$queryString = 'SELECT * FROM Products WHERE Products.Description LIKE ? ORDER BY Products.Description LIMIT 10 OFFSET ? * 10;';
				$sortValue = $az;
			} elseif ($orderByString == 'za'){
				$queryString =  'SELECT * FROM Products WHERE Products.Description LIKE ? ORDER BY Products.Description DESC LIMIT 10 OFFSET ? * 10;';
				$sortValue = $za;
			} elseif ($orderByString == 'price-up'){
				$queryString =  'SELECT * FROM Products WHERE Products.Description LIKE ? ORDER BY Products.Price LIMIT 10 OFFSET ? * 10;';
				$sortValue = $priceup;
			} elseif ($orderByString == 'price-down'){
				$queryString =  'SELECT * FROM Products WHERE Products.Description LIKE ? ORDER BY Products.Price DESC LIMIT 10 OFFSET ? * 10;';
				$sortValue = $pricedown;
			} elseif ($orderByString == 'popularity'){
				$sortValue = $popularity;
			}

		}
		// if the user did NOT provided a search string, assume an empty string
		else
		{
			$orderByString = "";
			$sortValue = "Sort By";
		}		

		//Task 9
		if(isset($_GET['page']))
		{
			$currentPage = intval($_GET['page']);
		}
		else
		{
			$currentPage = 0;
		}
				
		$nextPage =  $currentPage + 1; //Task 9A

		$previousPage =  $currentPage - 1;

		// connect to the database using our function (and enable errors, etc)
		$dbh = connectToDatabase();
		
		$statement = $dbh->prepare('SELECT COUNT(*) FROM Products  
			WHERE Products.Description LIKE ?');

		$statement->bindValue(1,$SqlSearchString);
		$statement->execute();
		$count = $statement->fetchColumn();


		echo "<div>";

		echo "<div class='dropdown show'>";
		echo "<a class='btn btn-secondary dropdown-toggle' href='#' role='button' id='sortProductsDropDown' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>$sortValue</a>";
	  
		echo "<div class='dropdown-menu dropdown-menu-end' aria-labelledby='dropdownMenuLink'>";
		echo  "<a class='dropdown-item' href='ProductList.php?search=$safeSearchString&orderby=popularity'>$popularity</a>";
		echo  "<a class='dropdown-item' href='ProductList.php?search=$safeSearchString&orderby=az'>$az</a>";
		echo  "<a class='dropdown-item' href='ProductList.php?search=$safeSearchString&orderby=za'>$za</a>";
		echo  "<a class='dropdown-item' href='ProductList.php?search=$safeSearchString&orderby=price-up'>$priceup</a>";
		echo  "<a class='dropdown-item' href='ProductList.php?search=$safeSearchString&orderby=price-down'>$pricedown</a>";
		echo "</div>";
	    echo "</div>";

	  	echo "</div>";
	
		echo "<nav>";
		echo "<ul class='pagination justify-content-center'>";

		if ($previousPage >= 0){
			echo "<li class='page-item'><a class='page-link' href='ProductList.php?page=$previousPage&search=$safeSearchString&orderby=$orderByString'>Previous</a></li>";
		} else {
			echo "<li class='page-item disabled'><a class='page-link' href='ProductList.php?page=$previousPage&search=$safeSearchString&orderby=$orderByString'>Previous</a></li>";
		}
		 
		  for ($items=0; $items < $count; $items +=10){
			$currentPageCount = $items/10;
			$currentPageCount;
			$displayPageCount = $currentPageCount + 1;
		  	
			if ($currentPage == $currentPageCount){
				echo "<li class='page-item active'><a class='page-link' href='ProductList.php?page=$currentPageCount&search=$safeSearchString&orderby=$orderByString'>$displayPageCount</a></li>";
			} else {
				echo "<li class='page-item'><a class='page-link' href='ProductList.php?page=$currentPageCount&search=$safeSearchString&orderby=$orderByString'>$displayPageCount</a></li>";
			}
		  }
		  
		  if (($currentPage + 1) * 10 < $count){
		  	echo "<li class='page-item'><a class='page-link' href='ProductList.php?page=$nextPage&search=$safeSearchString&orderby=$orderByString'>Next</a></li>";
		  } else {
			echo "<li class='page-item disabled'><a class='page-link' href='ProductList.php?page=$nextPage&search=$safeSearchString&orderby=$orderByString'>Next</a></li>";
		  }
		echo "</ul>";
		echo "</nav>";


		$statement = $dbh->prepare($queryString);  //Task 9

		
		$statement->bindValue(1,$SqlSearchString); //Task 7A
		$statement->bindValue(2,$currentPage); //Task 9
			
		
		//execute the SQL.
		$statement->execute();

		echo "<div class='row row-cols-1 row-cols-md-5 g-4 w-100'>";
		// get the results
		while($row = $statement->fetch(PDO::FETCH_ASSOC))
		{
			// Remember that the data in the database could be untrusted data. 
			// so we need to escape the data to make sure its free of evil XSS code.
			$ProductID = htmlspecialchars($row['ProductID'], ENT_QUOTES, 'UTF-8'); 
			$Price = htmlspecialchars($row['Price'], ENT_QUOTES, 'UTF-8'); 
			$Description = htmlspecialchars($row['Description'], ENT_QUOTES, 'UTF-8'); 
			
			echo "<div class='col'>";
			echo "<div class='card h-100'>";
			echo "<div class='card-header'>$ProductID</div>";
			echo "<div class='text-center'><a href='./ViewProduct.php?ProductID=$ProductID'><img class='h-70 w-50 ' src='../IFU_Assets/ProductPictures/$ProductID.jpg' class='card-img-top' alt=''/></a></div>";
			echo "<div class='card-body'>";
			echo "<p class='card-text'>$Description</p>";
			echo "<p class='card-text'>$Price</p>";
			echo "</div>";
			echo "</div>";
		  	echo "</div>";			
		}
		echo "</div>";
	?>

</body>
</html>