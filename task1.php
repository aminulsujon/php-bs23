<?php 
$servername = "localhost";$username = "root";$password = "";$dbname = "ecommerce";
$conn = new mysqli($servername,$username,$password,$dbname);
if ($conn->connect_error){die("Connection failed: " . $conn->connect_error);}
$sql_category_list = "SELECT 
    COUNT(item_category_relations.categoryId) as count, category.Name
    FROM `item_category_relations`
        LEFT JOIN category ON category.id = item_category_relations.categoryId
    WHERE `categoryId` > 0
    GROUP BY `categoryId`
    ORDER BY `count` DESC
    ";
$arr_category_list = $conn->query($sql_category_list);
?>
<!DOCTYPE html>
<html lang="en-US">
	<head>
		<title>Task 1 | PHP Developer Test</title>
        <style>h1{font-size:1rem;}table{border-top:1px solid #ddd;border-left:1px solid #ddd}td{border-right:1px solid #ddd;border-bottom:1px solid #ddd;padding:10px}</style>
	</head>
	<body>
    <h1>Task 1: Show all categories with total item and order categories by total Items (DESC)</h1>
    <table cellspacing=0>
    <?php
    $subTotal = 0;
    echo "<tr><td>Category Name</td><td>Total Items</td></tr>";
    if(!empty($arr_category_list) && $arr_category_list->num_rows > 0){
        $i = 1;
        while($rowf = $arr_category_list->fetch_assoc()) {
            echo '<tr><td>'.$rowf['Name'].'</td><td>'.$rowf['count'].'</td></tr>';
            $subTotal = $subTotal + $rowf['count'];
            $i++;
        }
    }
    ?>
    <tr>
        <td>Total</td><td><?= $subTotal?></td>
    </tr>
    </table>
    </body>
</html>
