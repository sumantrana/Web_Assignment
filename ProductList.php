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
	?>	
	<h1>Products List</h1>
	<?php 
		
		include('functions.php');
		
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

		echo "<form>"; //Task 7
		echo "<input name = 'search' type = 'text' value = '$safeSearchString' />"; //Task 8B
		echo "<input type = 'submit'/>"; //Task 7
		echo "</form>"; //Task 7
		
		//Task 9
		if(isset($_GET['page']))
		{
			$currentPage = intval($_GET['page']);
		}
		else
		{
			$currentPage = 0;
		}
				
		// echo "<form>";
		// echo "<input name = 'page' type = 'text' value = '$currentPage' />";
		// echo "<input type = 'submit'/>";
		// echo "</form>";
	

		
		$nextPage =  $currentPage + 1; //Task 9A
		// echo "<a href = 'ProductList.php?page=$nextPage&search=$safeSearchString'>Next Page</a>"; //Task 9B
		// echo "<br/>";

		$previousPage =  $currentPage - 1;
		if ($previousPage >= 0)
		// {
		// 	echo "<a href = 'ProductList.php?page=$previousPage&search=$safeSearchString'> Previous Page</a> <br/>";
		// }

		// connect to the database using our function (and enable errors, etc)
		$dbh = connectToDatabase();
		
		// select all the products.
		//$statement = $dbh->prepare('SELECT * FROM Products LIMIT 10 OFFSET ? * 10;');

		$statement = $dbh->prepare('SELECT COUNT(*) FROM Products  
			WHERE Products.Description LIKE ?');

		$statement->bindValue(1,$SqlSearchString);
		$statement->execute();
		$count = $statement->fetchColumn();
	
		echo "<nav>";
		echo "<ul class='pagination justify-content-center'>";
		  echo "<li class='page-item'><a class='page-link' href='ProductList.php?page=$previousPage&search=$safeSearchString'>Previous</a></li>";
		 
		  for ($items=0; $items < $count; $items +=10){
			$currentPageCount = $items/10;
			$currentPageCount += 1;
		  	echo "<li class='page-item'><a class='page-link' href='ProductList.php?page=$currentPageCount&search=$safeSearchString'>$currentPageCount</a></li>";
		  }
		  
		  echo "<li class='page-item'><a class='page-link' href='ProductList.php?page=$nextPage&search=$safeSearchString'>Next</a></li>";
		echo "</ul>";
	  	echo "</nav>";		

		
		$statement = $dbh->prepare('SELECT * FROM Products  
			WHERE Products.Description LIKE ? 
			LIMIT 10 OFFSET ? * 10;');  //Task 9

		
		$statement->bindValue(1,$SqlSearchString); //Task 7A
		$statement->bindValue(2,$currentPage); //Task 9
			
		
		//execute the SQL.
		$statement->execute();

		echo "<div class='row row-cols-1 row-cols-md-5 g-4'>";
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
			//echo "<div class='card h-100' style='width: 18rem;'>";
			echo "<div class='card-header'>$ProductID</div>";
			echo "<a href='./ViewProduct.php?ProductID=$ProductID'><img src='../IFU_Assets/ProductPictures/$ProductID.jpg' class='card-img-top' alt=''/></a>";
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