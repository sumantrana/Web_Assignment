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
	<h1>Customer List</h1>
	<?php
    
 		// connect to the database using our function (and enable errors, etc)
        $dbh = connectToDatabase();
         
		$statement = $dbh->prepare('SELECT * FROM Customers');
        $statement->execute();

        echo "<table class='table table-striped table-bordered w-100'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th scope='col'>ID</th>";
        echo "<th scope='col'>UserName</th>";
        echo "<th scope='col'>Name</th>";
        echo "<th scope='col'>Address</th>";
        echo "<th scope='col'>City</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";        
		      
        while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        
            $CustomerID = htmlspecialchars($row['CustomerID'], ENT_QUOTES, 'UTF-8'); 
            $UserName = htmlspecialchars($row['UserName'], ENT_QUOTES, 'UTF-8'); 
            $FirstName = htmlspecialchars($row['FirstName'], ENT_QUOTES, 'UTF-8'); 
            $LastName = htmlspecialchars($row['LastName'], ENT_QUOTES, 'UTF-8'); 
            $Address = htmlspecialchars($row['Address'], ENT_QUOTES, 'UTF-8'); 
            $City = htmlspecialchars($row['City'], ENT_QUOTES, 'UTF-8'); 
        
            echo "<tr>";
            echo "<th scope='row'>$CustomerID</th>";
            echo "<td>$UserName</td>";
            echo "<td>$FirstName $LastName</td>";
            echo "<td>$Address</td>";
            echo "<td>$City</td>";
            echo "</tr>";            

        }

        echo "</tbody>"; 
        echo "</table>"; 

    ?>