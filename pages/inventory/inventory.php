<?php
include_once '../../env/conn.php';
//$k = $_SESSION['option'];
?>

<!DOCTYPE html>
<html>
<head>
<title> Inventory </title>
<!-- ajax -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <!-- jquery -->
     <!--   <script src="jquery-3.5.1.min.js"></script>-->
<script type="text/javascript" src="inventory.js"></script> 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
       
</head>
<body >
<h1> Inventory </h1>

<div class="container-fluid" >
    <label for="sort">Sort by:</label>
    <select name="sort" id="sort" style="height:30px;">
    <option value="ID" selected >ID</option>
    <option value="Category">Category</option>
    <option value="PriceAsc"> <span>&#8593;</span>Price</option>
    <option value="PriceDesc"> <span>&#8595;</span>Price</option>
    <option value="Stocks">Stocks</option>
    <option value="Salability">Salability</option>
    </select>

    <input type="text" id="search" autocomplete="off" placeholder="Search" style="height:30px;">
</div>

<div id="display">
    <?php
         $sql = "SELECT * FROM item INNER JOIN inventory ON (item.item_ID = inventory.item_ID);";   
        $result = mysqli_query($conn,$sql);
        $resultCheck = mysqli_num_rows($result);
            
        echo "<table class='table'> 
                <tr>
                    <th> ID </th>
                    <th> Item </th>
                    <th> Unit </th>
                    <th> Brand </th>
                    <th> Retail Price </th>
                    <th> Markup </th>
                    <th> Stock </th>
                    <th> Category </th>
                </tr>";

        if ($resultCheck>0){
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['item_Stock']<=5){
                    echo "<tr class='table-danger'>";
                } else{
                    echo "<tr>";
                }   
                echo "<td>" .$row['item_ID']. "</td>";  
                echo "<td>". $row['item_Name']. "</td>";  
                echo "<td>" .$row['item_unit']. "</td>";  
                echo "<td>" . $row['item_Brand'] . "</td>";  
                echo "<td>" . $row['item_RetailPrice']. "</td>"; 
                echo "<td>" .$row['Item_markup']. "</td>";
                echo "<td>" .$row['item_Stock']. "</td>";  
                echo "<td>" .$row['item_category']. "</td>";       
                echo "</tr>";   
            }
        } 

        mysqli_close($conn);
        echo "</table>";

?>
</div>

</body>
</html>