<!doctype html>
<html>
<head>
	<meta charset="UTF-8" /> 
	<title>Customer List</title>
	<link rel="stylesheet" type="text/css" href="shopstyle.css" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
	<?php
		include('TopDiv.php');
		include('NavBar.php');
        include('functions.php');
	?>	
	<h1 class="ms-5">Business Report</h1>
	<?php
    
 		// connect to the database using our function (and enable errors, etc)
        $dbh = connectToDatabase();
         
		$statement = $dbh->prepare('
            select Products.ProductID as ProductID, Description, Price, BrandName, count(*) as Popularity, sum(OrderProducts.Quantity) as TotalQuantity, sum(OrderProducts.Quantity * Price) as TotalRevenue, max(Timestamp) as LastPurchaseDate
            from Products
            INNER JOIN Brands ON Products.BrandID = Brands.BrandID
            inner join OrderProducts on Products.ProductID = OrderProducts.ProductID
            inner join Orders on OrderProducts.OrderID = Orders.OrderID
            group by OrderProducts.ProductID
            order by TotalRevenue desc
        ');

        $statement->execute();

        echo "<table class='table table-striped table-bordered ms-5 me-5 w-auto'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th scope='col'>Product ID</th>";
        echo "<th scope='col'>Description</th>";
        echo "<th scope='col'>Price</th>";
        echo "<th scope='col'>Brand</th>";
        echo "<th scope='col'>Popularity</th>";
        echo "<th scope='col'>Total Quantity</th>";
        echo "<th scope='col'>Total Revenue</th>";
        echo "<th scope='col'>Last Purchase Date Time</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";        
		      
        while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        
            $ProductID = htmlspecialchars($row['ProductID'], ENT_QUOTES, 'UTF-8'); 
            $Description = htmlspecialchars($row['Description'], ENT_QUOTES, 'UTF-8'); 
            $Price = htmlspecialchars($row['Price'], ENT_QUOTES, 'UTF-8'); 
            $Brand = htmlspecialchars($row['BrandName'], ENT_QUOTES, 'UTF-8');
            $Popularity = htmlspecialchars($row['Popularity'], ENT_QUOTES, 'UTF-8'); 
            $TotalQuantity = htmlspecialchars($row['TotalQuantity'], ENT_QUOTES, 'UTF-8'); 
            $TotalRevenue = htmlspecialchars($row['TotalRevenue'], ENT_QUOTES, 'UTF-8'); 
            $LastPurchaseTimestamp = htmlspecialchars($row['LastPurchaseDate'], ENT_QUOTES, 'UTF-8'); 

            $LastPurchaseDateTime = date('Y-m-d H:i:s', $LastPurchaseTimestamp);
        
            echo "<tr>";
            echo "<th scope='row'><a href='./ViewProduct.php?ProductID=$ProductID'>$ProductID</a></th>";
            echo "<td>$Description</td>";
            echo "<td>$Price</td>";
            echo "<td>$Brand</td>";
            echo "<td>$Popularity</td>";
            echo "<td>$TotalQuantity</td>";
            echo "<td>$TotalRevenue</td>";
            echo "<td>$LastPurchaseDateTime</td>";
            echo "</tr>";            

        }

        echo "</tbody>"; 
        echo "</table>"; 

    ?>