<?php
    error_reporting(0);
    include_once '../../env/conn.php';

    // DELETE ITEM FROM INVENTORY
    if (isset($_POST['delete1'])) {
        echo "delete clicked";
        $itemID = $_POST['itemID1'];
        $deleteItem = "UPDATE inventory SET inventoryItem_Status = 0 WHERE branch_ID =1 AND item_ID = '$itemID';";
        $sqlDelete = mysqli_query($conn,$deleteItem);
        if ($sqlDelete) {
          echo "deleted";
        } else {
          echo mysqli_error($conn);
        }
        header("Location: ./inventory.php");
        unset($_SESSION['delete1']);
    }
    // EDIT AN ITEM FROM INVENTORY
    if(isset($_POST['edit'])){
        $_SESSION['itemID'] = $_POST['itemID'];
        header("Location: ./editinventory.php");
        unset($_POST['edit']);
    }
    // SQL QUERIES ==========================================================================================
    // FROM SEARCH TAB
    if (isset($_POST['search'])) {
        $Name = $_POST['search'];
        if ($Name!="") {    
            $sql = "SELECT * FROM item INNER JOIN inventory ON (item.item_ID = inventory.item_ID) WHERE  inventoryItem_Status = 1 AND (item_Name LIKE '%$Name%' OR item_Brand LIKE '%$Name%' OR item_category LIKE '%$Name%'); ";
        } else {
            $sql = "SELECT * FROM item INNER JOIN inventory ON (item.item_ID = inventory.item_ID) WHERE  inventoryItem_Status = 1;"; 
        }
    // FROM SORT
    } else if (isset($_POST['selected'])) {
        $k = $_POST['selected'];
        $_SESSION['option'] = $_POST['selected'];
        
        if ($k == "PriceAsc") {
            $sql = "SELECT * FROM item INNER JOIN inventory ON (item.item_ID = inventory.item_ID) WHERE  inventoryItem_Status = 1 ORDER BY item_RetailPrice ASC;"; 
        } else if ($k == "PriceDesc") {
            $sql = "SELECT * FROM item INNER JOIN inventory ON (item.item_ID = inventory.item_ID) WHERE  inventoryItem_Status = 1 ORDER BY item_RetailPrice DESC;"; 
        } else if ($k == "item_Stock") {
            $sql = "SELECT * FROM item INNER JOIN inventory ON (item.item_ID = inventory.item_ID) WHERE  inventoryItem_Status = 1 ORDER BY $k ASC;"; 
        } else if ($k == "Category") {
            $sql = "SELECT * FROM item INNER JOIN inventory ON (item.item_ID = inventory.item_ID) WHERE  inventoryItem_Status = 1 ORDER BY  item_category,item_Name ASC;"; 
        } else if ($k == "ID"){
            $sql = "SELECT * FROM item INNER JOIN inventory ON (item.item_ID = inventory.item_ID) WHERE  inventoryItem_Status = 1 ORDER BY inventory.item_ID;"; 
        } else if ($k == "Salability"){
            $sql = "SELECT * FROM item INNER JOIN inventory ON (item.item_ID = inventory.item_ID) INNER JOIN (SELECT SUM(orderItems_Quantity) as sales_sum, item_ID as order_itemID FROM order_items GROUP BY item_ID) as orders ON (inventory.item_ID = orders.order_itemID) WHERE  inventoryItem_Status = 1 ORDER BY sales_sum DESC;"; 
        } else {
            $sql = "SELECT * FROM item INNER JOIN inventory ON (item.item_ID = inventory.item_ID) WHERE  inventoryItem_Status = 1;"; 
        }
    // FROM CATEGORY  
    } else if (isset($_POST['category'])) {
        $category= $_POST['category'];
        echo "<h4> ".$category . "</h4>";
        if ($category=='All') {
            $sql = "SELECT * FROM item INNER JOIN inventory ON (item.item_ID = inventory.item_ID) WHERE  inventoryItem_Status = 1 ";
        } else {
            $sql = "SELECT * FROM item INNER JOIN inventory ON (item.item_ID = inventory.item_ID) WHERE  item_category = '$category' AND  inventoryItem_Status = 1";
        }
    // DEFAULT: BY ID    
    }  else {
            $sql = "SELECT * FROM item INNER JOIN inventory ON (item.item_ID = inventory.item_ID) WHERE  inventoryItem_Status = 1 ORDER BY inventory.item_ID;"; 
    }  
    // END OF SQL QUERIES ==========================================================================================
    
    // SHOW RESULT OF QUERY
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
                ";
                if ($k == "Salability"){
                    echo "<th> Total Sales</th>"; 
                }  
            echo "<th> </th>
                    </tr>";

    if ($resultCheck>0){
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['item_Stock']<=10){ //LOW ON STOCK ======================================
                echo "<tr class='table-danger'>";
                //ADDING IN PENDING ORDERS===================================================================
                if ($row['in_pending']==0) {
                    $_SESSION['pending_ItemID'] = $row['item_ID'];
                    echo $_SESSION['pending_ItemID'];
                    include 'addpending.php';
                }   // END OF ADDING IN PENDING ORDERS =====================================================
                } else{   //NOT LOW ON STOCK =================================================
                    echo '<tr>';
                }   
                echo "<td>" .$row['item_ID']. "</td>";  
                echo "<td>". $row['item_Name']. "</td>";  
                echo "<td>" .$row['item_unit']. "</td>";  
                echo "<td>" . $row['item_Brand'] . "</td>";  
                echo "<td>" . $row['item_RetailPrice']. "</td>"; 
                echo "<td>" .$row['Item_markup']. "</td>";
                // echo "<td> <input type=number name=itemStock id='itemStock' min=1 value=" .$row['item_Stock']." style='width:70px;'/> </td>";  
                echo "<td>" .$row['item_Stock']. "</td>"; 
                echo "<td>" .$row['item_Category']. "</td>";   
                if ($k == "Salability"){
                    echo "<td>" .$row['sales_sum']. "</td>"; 
                }   
                ?>
                <!--DELETE AND EDIT BUTTON-->
                <td style="width:100px;"> <button type="button" class="btn editbtn" style="float:left;"> <i class='fas fa-edit'></i> </button>
                    <form action="search_sort.php" class="mb-1" method="post">
                        <button class="btn" name="delete1" type="submit" style="float:right; padding-left:0px;"><i class='fas fa-trash'></i></button>
                        <input type=hidden name=itemID1 value=<?php echo $row['item_ID']?>>
                        
                    </form>
                </td>    
            </tr>
        <?php  
        } // END OF WHILE
    } // END OF RESULTCHECK
    echo "</table>";
?>
<script>
           $(document).ready(function(){
              $('.editbtn').on('click',function(){
                $('#staticBackdrop').modal('show');
                $tr = $(this).closest('tr');

                var data = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();

                console.log(data);

                $('#editID').val(data[0]);
                $('#editName').val(data[1]);
                $('#editUnit').val(data[2]);
                $('#editBrand').val(data[3]);
                $('#editRetail').val(data[4]);
                $('#editMarkup').val(data[5]);
                $('#editStock').val(data[6]);
                $('#editCategory').val(data[7]);
                const $select = document.querySelector('#item_Category');
                $select.value = data[7];
                document.getElementById("labelID").innerHTML = "Item ID: " + data[0];
              });
           });
         </script>
