<!DOCTYPE html>
<html>
<head>
  <title>Add Records in Database</title>
</head>
<body>

<?php

include_once '../../env/conn.php';

//if (isset($_POST['order'])) {
    $orderItemID = $_SESSION['orderItemID'];
    $orderItemSupp = $_SESSION['orderItemSupp'];
    $supplier = "SELECT * FROM item INNER JOIN supplier_item ON (item.item_ID = supplier_item.item_ID) INNER JOIN supplier ON (supplier_item.supplier_ID = supplier.supplier_ID) WHERE supplier_item.supplier_ID='$orderItemSupp' AND supplier_item.item_ID = '$orderItemID';";

    $resultSupplier = mysqli_query($conn,$supplier);
    $resultCheckSupplier = mysqli_num_rows($resultSupplier);
        if ($resultCheckSupplier>0){
          while ($rowitems = mysqli_fetch_assoc($resultSupplier)) {
            $item_CostPrice = $rowitems['supplierItem_CostPrice'];
            
                    ?>
                    <h2><?php echo $rowitems['item_Name']; ?> </h2>
                    <p><?php echo $rowitems['item_Brand']; ?> </p>
                    <p><?php echo $rowitems['supplierItem_CostPrice']. " pesos/".$rowitems['item_unit']; ?> </p>
                    <p> Supplier: <a href='../supplier/suppliertable.php?supplier_ID=<?php  echo$rowitems['supplier_ID']?>' > <?php echo $rowitems['supplier_Name']?> </a> </p> <br/>

                    <?php 
                    $item_category = $rowitems['item_Category'];
          } 
        } 
        $dummyRetail = $item_CostPrice + $item_CostPrice*0.5;
//}

if(isset($_POST['submit']))
{		
    
	$Item_markup= $_POST['Item_markup'];
    $_SESSION['addInventory_markup'] = $Item_markup;
	$item_Stock= $_POST['item_Stock'];
	$item_category= $_POST['item_category'];
    $_SESSION['addInventory_category'] = $item_category;
	$item_RetailPrice = $item_CostPrice+($item_CostPrice*$Item_markup/100);
    echo $item_RetailPrice;

    $updateCateg = "UPDATE item set item_Category = '$item_category' WHERE item_ID = '$orderItemID'";

    $sqlupdateCateg = mysqli_query($conn,$updateCateg);
    if ($sqlupdateCateg) {
        echo "categ changes";
    } else {
        echo mysqli_error($conn);
    }
    //see if item already pending in supplier transactions
    $sql2 = "SELECT * FROM supplier_Transactions WHERE supplier_ID = '$orderItemSupp' AND transaction_Status = 0;";   
    $result2 = mysqli_query($conn,$sql2);
    $resultCheck2 = mysqli_num_rows($result2);
        
    if ($resultCheck2>0){
        while ($row2 = mysqli_fetch_assoc($result2)) {
              $Transaction = $row2['transaction_ID'];
        }
    } else {
        $timestamp = date('Y-m-d H:i:s');
        $insert = "INSERT INTO supplier_Transactions (supplier_ID, transaction_Date, transaction_Status, transaction_TotalPrice)
        VALUES ('$orderItemSupp', '$timestamp', 0, 0 );";
        $sqlInsert = mysqli_query($conn, $insert);
        if ($sqlInsert) {
            $last_id = mysqli_insert_id($conn);
            $Transaction = $last_id;
            echo "New Transaction Added";
        } else {
            echo mysqli_error($conn);
        }
    }
    
    //insert in transaction items
    $items_total = $item_CostPrice*$item_Stock;

    $sql2 = "SELECT * FROM transaction_Items WHERE transaction_ID = '$Transaction' AND item_ID = '$orderItemID';";   
    $result2 = mysqli_query($conn,$sql2);
    $resultCheck2 = mysqli_num_rows($result2);
        
    if ($resultCheck2>0){
        while ($row2 = mysqli_fetch_assoc($result2)) {
            echo "item already in transaction ID: " . $Transaction. ". Quantity will be added to pending quantity. <br/>";
            $updatePendingItem = "UPDATE transaction_Items SET transactionItems_Quantity =transactionItems_Quantity+'$item_Stock', transactionItems_CostPrice = $item_CostPrice, transactionItems_TotalPrice = '$items_total' WHERE transaction_ID = '$Transaction' AND item_ID = '$orderItemID';";
             $sqlUpdatePendingItem = mysqli_query($conn,$updatePendingItem);
             if ($sqlUpdatePendingItem) {
                echo 'updated pending item';
            } else {
                echo mysqli_error($conn);
            }
        }
    } else {
        $insert = "INSERT INTO transaction_items(transaction_ID, item_ID, transactionItems_Quantity, transactionItems_CostPrice, transactionItems_TotalPrice)
        VALUES ('$Transaction', '$orderItemID', '$item_Stock', '$item_CostPrice' , '$items_total');";
        $sqlInsert = mysqli_query($conn, $insert);
        if ($sqlInsert) {
            echo 'added in pending orders';
        } else {
            echo mysqli_error($conn);
        }
        
    }

    

    /*$insert = mysqli_query($conn,"INSERT INTO inventory ". "(branch_ID,item_ID, item_Stock, item_RetailPrice, item_category, Item_markup,  in_pending) ". "
			  VALUES(1, '$orderItemID', '$item_Stock', '$item_RetailPrice','$item_category',  '$Item_markup',0 )");
			
               
    if(!$insert)
    {
        echo mysqli_error();
    }
    else
    {
        echo "Record added successfully.";
    } */
}

mysqli_close($conn); // Close connection

?>



<form action="./addinventory.php" method="post" >
        Item Retail Price:
        <input type="number" type="number" step="0.01" value="<?php echo $dummyRetail?>" name="item_RetailPrice" id="item_RetailPrice" > 
    </p>  
	<p>
        Item Markup:
        <input type="number" step="0.01" name="Item_markup" id="Item_markup" value="0.5" required>
    </p>  
	<p>
        Item Stock:
        <input type="text" name="item_Stock" id="item_Stock" required>
    </p>  
    
	<p>
        Item Category:
        
        <select name="item_category" id="item_category" style="height:30px;">
        <option value="<?php echo $item_category?>" ><?php echo $item_category?></option>
            <option value="Electrical" >Electrical</option>
            <option value="Plumbing">Plumbing</option>
            <option value="Architectural"> Architectural</option>
            <option value="Paints">Paints</option>
            <option value="Bolts">Bolts</option>
            <option value="Tools">Tools</option>
        </select>
    </p>  

  <input type="submit" name="submit" value="Submit">
  <button type="button" onclick="location.href='inventory.php'">Go back </button>
</form>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script>

    $('#Item_markup').change(function() {
        var costPrice = <?php echo $item_CostPrice; ?>;
        $('#item_RetailPrice').val( (costPrice + costPrice*$('#Item_markup').val()).toFixed(1));
    });

    $('#item_RetailPrice').keyup(function() {
        var costPrice = <?php echo $item_CostPrice; ?>;
        $('#Item_markup').val(($('item_RetailPrice').val() - costPrice)/costPrice);
    });
  </script>

</body>
</html>

